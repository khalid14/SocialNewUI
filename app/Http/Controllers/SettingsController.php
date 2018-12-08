<?php

namespace App\Http\Controllers;

use App\Contracts\DefinitionRepository;
use App\Contracts\SettingRepository;
use App\Contracts\ShopRepository;
use App\Presenters\SettingPresenter;
use App\Transformers\SettingTransformer;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    /**
     * @var ShopRepository
     */
    protected $shopRepo;

    protected $settingRepo;

    public function __construct(ShopRepository $shopRepo, SettingRepository $settingRepo, DefinitionRepository $definitionRepo)
    {
        $this->shopRepo = $shopRepo;
        $this->settingRepo = $settingRepo;

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        $shop = $request->session()->get('shop');
        $shopObj = $this->shopRepo->findByField('name', $shop)->first();
        return $shopObj->setting->transform();
    }

    public function save(Request $request)
    {
        $data = $request->all();
        return $this->settingRepo->update($data, $data['id']);
    }

}
