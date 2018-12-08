<?php

namespace App\Http\Controllers;

use App\Models\Sprint;

use App\Contracts\ShopRepository;
use App\Traits\ShopifyTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use DateTime;
use Illuminate\Routing\Controller as BaseController;

class SprintController extends BaseController
{

    use ShopifyTrait;
    /**
     * @var ShopRepository
     */
    protected $shopRepo;
    
    /**
     * Constructor function
     */
    public function __construct(ShopRepository $shopRepo)
    {
        $this->shopRepo = $shopRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shop = $request->session()->get('shop');
        $shop = $this->shopRepo->findByField('name', $shop)->first();
        $shopify = $this->getShopifyObj($shop);
        $shopInfo = $shopify->call(['URL' => '/admin/products.json', 'METHOD' => 'GET']);
        $custCollections = $shopify->call(['URL' => '/admin/custom_collections.json', 'METHOD' => 'GET']);
        $smartCollections = $shopify->call(['URL' => '/admin/smart_collections.json', 'METHOD' => 'GET']);

        $collectionObj = (object)array_merge_recursive((array)$custCollections , (array)$smartCollections);
        $collectionObj = array_flatten($collectionObj);

        $filters = $vendor = $tags = $p_types = $collections= array();
        foreach ($shopInfo->products as $obj) {

            $obj->isSelected = false;
            foreach ($obj as $key => $value) {
                if($key == 'product_type'){

                    $objVar = array("type"=>"product_type", "value"=>$value);
                    if (!in_array($filters, $objVar)) {
                        array_push($filters, $objVar);
                        array_push($p_types, $objVar);
                    }

                }elseif($key == 'vendor'){

                    $objVar = array("type"=>"vendor", "value"=>$value);
                    if (!in_array($filters, $objVar)) {
                        array_push($filters, $objVar);
                        array_push($vendor, $objVar);
                    }
                }
                // elseif($key == 'tags'){

                //     $objVar = array("type"=>"tags", "value"=>$value);
                //     if (!in_array($filters, $objVar)) {
                //         array_push($filters, $objVar);
                //         array_push($tags, $objVar);
                //     }
                // }
            }
        }
        
        foreach($collectionObj as $key => $value){

            $value->isSelected = false;
            $objVarColl = array("type"=>"collection", "value"=>$value->title, "id"=>$value->id);
            if (!in_array($filters, $objVarColl)) {
                array_push($collections, $objVarColl);
            }
        }

        $p_types = array_unique($p_types, SORT_REGULAR);
        $vendor = array_unique($vendor, SORT_REGULAR);
        // $tags = array_unique($tags, SORT_REGULAR);
        $filters = array_unique($filters, SORT_REGULAR);
        $collections = array_unique($collections, SORT_REGULAR);

        return response()->json([
            'p_types'     => $p_types,
            'vendor'      => $vendor,
            // 'tags'    => $tags,
            'filters'     => $filters,
            'collections' => $collections,
            'results'     => $shopInfo
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop = $request->session()->get('shop');
        $shop = $this->shopRepo->findByField('name', $shop)->first();

        $start_time = new \DateTime($request->cron_time_from);
        $end_time = new \DateTime($request->cron_time_to);
        $start_time = $start_time->format('H:i:s');
        $end_time = $end_time->format('H:i:s');

        $sprint = new Sprint;
        $sprint->name       = $request->sname;
        $sprint->posts      = $request->post;
        $sprint->days       = $request->every_days;
        $sprint->from       = $start_time;
        $sprint->to         = $end_time;
        $sprint->products   = json_encode($request->products_ids);
        $sprint->details    = json_encode($request->settings);
        $sprint->type       = $request->type;
        $sprint->status     = $request->status;
        $sprint->shop_id    = $shop->id;

        $sprint->save();

        return response()->json([
            'status' => true,
            'message' => 'sprint is create successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFilteredProducts( Request $request)
    {
        $shop = $request->session()->get('shop');
        $shop = $this->shopRepo->findByField('name', $shop)->first();
        $shopify = $this->getShopifyObj($shop);

        if($request->type == 'product_type'){
            $result = $shopify->call(['URL' => '/admin/products.json?product_type='.$request->prodFilter, 'METHOD' => 'GET']);
        }else if($request->type == 'vendor'){
            $result = $shopify->call(['URL' => '/admin/products.json?vendor='.$request->prodFilter, 'METHOD' => 'GET']);
        }else if($request->type == 'collection'){
            $result = $shopify->call(['URL' => '/admin/products.json?collection_id='.$request->id, 'METHOD' => 'GET']);
        }
        // else if($request->type == 'tags'){
        //     $result = $shopify->call(['URL' => '/admin/products.json?tags='.urlencode($request->prodFilter), 'METHOD' => 'GET']);
        // }

        $shopInfo = $shopify->call(['URL' => '/admin/products.json', 'METHOD' => 'GET']);
        $custCollections = $shopify->call(['URL' => '/admin/custom_collections.json', 'METHOD' => 'GET']);
        $smartCollections = $shopify->call(['URL' => '/admin/smart_collections.json', 'METHOD' => 'GET']);

        $collectionObj = (object)array_merge_recursive((array)$custCollections , (array)$smartCollections);
        $collectionObj = array_flatten($collectionObj);

        $filters = $vendor = $tags = $p_types = $collections = array();
        foreach ($shopInfo->products as $obj) {

            $obj->isSelected = false;
            foreach ($obj as $key => $value) {

                if($key == 'product_type'){
                    $objVar = array("type"=>"product_type", "value"=>$value);
                    if (!in_array($filters, $objVar)) {
                        array_push($filters, $objVar);
                        array_push($p_types, $objVar);
                    }

                }elseif($key == 'vendor'){
                    $objVar = array("type"=>"vendor", "value"=>$value);
                    if (!in_array($filters, $objVar)) {
                        array_push($filters, $objVar);
                        array_push($vendor, $objVar);
                    }
                }
                // elseif($key == 'tags'){

                //     $objVar = array("type"=>"tags", "value"=>$value);
                //     if (!in_array($filters, $objVar)) {
                //         array_push($filters, $objVar);
                //         array_push($tags, $objVar);
                //     }
                // }
            }
        }

        foreach($collectionObj as $key => $value){
            $value->isSelected = false;
            $objVarColl = array("type"=>"collection", "value"=>$value->title, "id"=>$value->id);
            if (!in_array($filters, $objVarColl)) {
                array_push($collections, $objVarColl);
            }
        }

        $p_types = array_unique($p_types, SORT_REGULAR);
        $vendor = array_unique($vendor, SORT_REGULAR);
        // $tags = array_unique($tags, SORT_REGULAR);
        $collections = array_unique($collections, SORT_REGULAR);
        $filters = array_unique($filters, SORT_REGULAR);
        return response()->json([
            'p_types' => $p_types,
            'vendor' => $vendor,
            // 'tags' => $tags,
            'collections' => $collections,
            'filters' => $filters,
            'results' => $result
        ]);
    }


     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProductByFilterType( Request $request)
    {
        $filters = $vendor = $p_types = $tags = $vendorRes = $p_typeRes = $tagRes = $collections = $collectionObj = array();
        $shop = $request->session()->get('shop');
        $shop = $this->shopRepo->findByField('name', $shop)->first();
        $shopify = $this->getShopifyObj($shop);


        if($request->selectedFilter == 'Collections'){

            $custCollections = $shopify->call(['URL' => '/admin/custom_collections.json', 'METHOD' => 'GET']);
            $smartCollections = $shopify->call(['URL' => '/admin/smart_collections.json', 'METHOD' => 'GET']);

            $collectionObj = (object)array_merge_recursive((array)$custCollections , (array)$smartCollections);
            $collectionObj = array_flatten($collectionObj);
        }
        $shopInfo = $shopify->call(['URL' => '/admin/products.json', 'METHOD' => 'GET']);
        $result = $shopInfo;


        if($request->selectedFilter == 'Collections'){


            foreach ($collectionObj as $key => $value) {
                $value->isSelected = false;
                $objVarColl = array("type" => "collection", "value" => $value->title, "id" => $value->id);
                if (!in_array($filters, $objVarColl)) {
                    array_push($collections, $objVarColl);
                }
            }
            $collections = array_unique($collections, SORT_REGULAR);
            $filters = array_unique($filters, SORT_REGULAR);

        }else {

            foreach ($shopInfo->products as $obj) {

                $obj->isSelected = false;
                foreach ($obj as $key => $value) {

                    if ($key == 'product_type' && $request->selectedFilter == 'Type') {
                        $objVar = array("type" => "product_type", "value" => $value);
                        if (!in_array($filters, $objVar)) {
                            array_push($filters, $objVar);
                            array_push($p_types, $objVar);
                        }
                        array_push($p_typeRes, $obj);

                    } elseif ($key == 'vendor' && $request->selectedFilter == 'Vendors') {

                        $objVar = array("type" => "vendor", "value" => $value);
                        if (!in_array($filters, $objVar)) {
                            array_push($filters, $objVar);
                            array_push($vendor, $objVar);
                        }
                        array_push($vendorRes, $obj);
                    }
                    // elseif($key == 'tags'){

                    //     $objVar = array("type"=>"tags", "value"=>$value);
                    //     if (!in_array($filters, $objVar)) {
                    //         array_push($filters, $objVar);
                    //         array_push($tags, $objVar);
                    //     }
                    //     array_push($tagRes, $obj);
                    // }
                }
            }

            $p_types = array_unique($p_types, SORT_REGULAR);
            $vendor = array_unique($vendor, SORT_REGULAR);
            // $tags = array_unique($tags, SORT_REGULAR);
            $filters = array_unique($filters, SORT_REGULAR);
        }

        return response()->json([
            'p_types'       => $p_types,
            'vendor'        => $vendor,
            // 'tags'      => $tags,
            'filters'       => $filters,
            'collections'   => $collections,
            'results'       => $result,
            'vendorRes'     => $vendorRes,
            'p_typeRes'     => $p_typeRes,
            // 'tagRes'    => $tagRes
        ]);
    }
}
