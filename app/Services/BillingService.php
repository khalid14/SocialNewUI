<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 3/8/2018
 * Time: 02:23 PM
 */

namespace App\Services;


use App\Contracts\BillingRepository;
use App\Models\Shop;
use App\Traits\ShopifyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class BillingService
{
    use ShopifyTrait;

    /**
     * @var BillingRepository
     */
    protected $repository;

    /**
     * @var Shop
     */
    protected $shop;

    /**
     * BillingService constructor.
     * @param BillingRepository $billingRepository
     */
    public function __construct(BillingRepository $billingRepository)
    {
        $this->repository = $billingRepository;
    }

    /**
     * @param Shop $shop
     * @return BillingService
     */
    public function setShop(Shop $shop)
    {
        $this->shop = $shop;
        return $this;
    }

    /**
     * Check if we should charge the customer or whitelist them
     * @return string
     * @throws \Exception
     */
    public function inspect()
    {
        if (!env('SHOPIFY_CHARGE')) {
            return route('landing-page');
        } elseif (env('SHOPIFY_SUPPORT_FREEPASS') && $this->shop->free_pass == true) {
            return route('pages.freePass');
        } elseif (env('SHOPIFY_SUPPORT_AFFILIATES') && $this->shop->plan == 'affiliate') {
            return route('pages.affiliate');
        } else {
            $this->setShop($this->shop);
            $this->cleanup();
            return $this->enroll();
        }
    }

    /**
     * enroll a shop into billing module
     * @return mixed
     * @throws \Exception
     */
    public function enroll()
    {
        try {
            $trial = $this->calculateTrial();
            $shopify = $this->getShopifyObj($this->shop);
            $resp = $shopify->call([
                'METHOD'    => 'POST',
                'URL'       => '/admin/recurring_application_charges.json',
                'DATA'      => [
                    'recurring_application_charge' => [
                        'name'          => $trial['message'],
                        'price'         => env('SHOPIFY_PRICE'),
                        'return_url'    => route('shopify-charge-callback'),
                        'trial_days'    => $trial['days'],
                        'test'          => env('SHOPIFY_SANDBOX')
                    ]
                ]
            ]);

            $params = $resp->recurring_application_charge;
            $params->shop_id = $this->shop->id;
            $params->shop_name = $this->shop->name;
            $this->repository->create(get_object_vars($params));

            return $params->confirmation_url;
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function actUponDecision(Request $request)
    {
        try {
            $chargeId = $request->get('charge_id');
            $billing = $this->repository->findByField('shopify_billing_id', $request->get('charge_id'))->first();
            $this->setShop($billing->shop);
            $shopify = $this->getShopifyObj($billing->shop);
            $charge = $shopify->call(['URL' => 'admin/recurring_application_charges/'.$chargeId.'.json', 'METHOD' => 'GET']);
            $chargeDetails = $charge->recurring_application_charge;
            $billing->status = $chargeDetails->status;
            $billing->save();

            if ($chargeDetails->status == 'declined') {
                return redirect()->route('pages.requestBilling');
            } elseif ($chargeDetails->status == 'accepted') {
                return $this->activate($charge);
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
            return '';
        }
    }

    public function activate($chargeObj)
    {
        $charge = json_decode(json_encode($chargeObj), true);
        $shopify = $this->getShopifyObj($this->shop);
        $billing = $this->shop->billing;

        $resp = $shopify->call([
            'METHOD'    => 'POST',
            'URL'       => '/admin/recurring_application_charges/'.$chargeObj->recurring_application_charge->id.'/activate.json',
            'DATA'      => $charge
        ]);
        
        if (isset($resp->recurring_application_charge)) {
            $billing->activated_on = $resp->recurring_application_charge->activated_on;
            $billing->billing_on = $resp->recurring_application_charge->billing_on;
            $billing->status = $resp->recurring_application_charge->status;
            $billing->save();
        }
        
        return redirect(route('landing-page'));
    }

    public function cleanup()
    {
        try {
            $shopify = $this->getShopifyObj($this->shop);
            $oldCharges = $shopify->call(['URL' => 'admin/recurring_application_charges.json', 'METHOD' => 'GET']);

            foreach ($oldCharges->recurring_application_charges as $crntCharge) {
                if ($crntCharge->status == 'active') {
                    $del = $shopify->call(['URL' => 'admin/recurring_application_charges/'.$crntCharge->id.'.json', 'METHOD' => 'DELETE']);
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function optout()
    {
        try {
            $billing = $this->shop->billing;
            if ($billing->status == 'declined') {
                $billing->forceDelete();
            } else {
                $billing->status = 'cancelled';
                $billing->save();
                $billing->delete();
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function hasComplied()
    {
        return $this->getStatus() == 'accepted' ? true : false;
    }

    public function hasDeclined()
    {
        return $this->getStatus() == 'declined' ? true : false;
    }

    public function getStatus()
    {
        return $this->shop->billing->status;
    }

    /**
     * helper method to check if use is coming back,
     * 1. if they opted out and coming back within trial period, then continue trial
     * 2. if they opted out and comeback in same month, give remainder as trial
     */
    public function calculateTrial()
    {
        $trial = intval(env('SHOPIFY_TRIAL_DAYS'));
        $message = 'Recurring Application Charge';
        $lastBill = $this->shop->lastBill();

        if ($trial > 0 && $lastBill != null && $lastBill->status != 'declined') {

            $now = Carbon::now()->addDay();
            $installedOn = $lastBill->activated_on;
            $billedOn = $lastBill->billing_on;
            $uninstalledOn = $lastBill->deleted_at;

            $daysInstalled = $uninstalledOn->diffInDays($installedOn);
            $wasCharged = $uninstalledOn->greaterThan($billedOn);
            $goodThru = $billedOn->copy()->addDays(30);
            
            $trialDays = $lastBill->trial_days;

            // ----- check if user is still in trial days
            if ($now->diffInDays($uninstalledOn) <= env('SHOPIFY_REINSTALL_BUFFER_DAYS')) {
                $trial = $daysInstalled > $trialDays ? 0 : $trialDays - $daysInstalled;
            }

            // ----- check if charge accepted, and they are billed for the month ...
            if ($wasCharged && $lastBill->status == 'cancelled' && $billedOn->lessThanOrEqualTo($now) && $goodThru->greaterThan($now)) {
                $trial = $now->diffInDays($goodThru);

                $message = 'You were last billed on '.$billedOn->toDateString(). ', so you are good through '. $goodThru->toDateString()
                    .'. Therefore we are giving you rest of the ' . $trial . ' days as free trial and will start charging you after that.';
            }
        }

        return [
            'days'      => $trial,
            'message'   => $message
        ];
    }

}