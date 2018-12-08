<?php

namespace App\Repositories;
use App\Contracts\ShopRepository;
use Illuminate\Container\Container as Application;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\SocialMediaRepository;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

/**
 * Class SocialMediaRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class SocialMediaRepositoryEloquent extends BaseRepository implements SocialMediaRepository
{

    /**
     * @var ShopRepository
     */
    protected $shopRepository;

    public function __construct(Application $app, ShopRepository $shopRepository)
    {
        parent::__construct($app);
        $this->shopRepository = $shopRepository;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SocialMedia::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function saveAccessToken(Request $request, $data){
        try {
            $shop = $request->session()->get('shop');
            $shop = $this->shopRepository->findByField('name', $shop)->first();

            $this->updateOrCreate(
                [
                    'shop_id'=>$shop->id,
                ],
                [
                    'social' => $data,
                ]
            );
            return [
                'status' => 'success'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'failure',
                'message' => $e
            ];
        }
    }
}
