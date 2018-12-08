<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Contracts\SocialMediaRepository;
use App\Services\SocialMediaService;

use App\Http\Requests;
/**
 * Class SocialMediaController.
 *
 * @package namespace App\Http\Controllers;
 */
class SocialMediaController extends Controller
{
    /**
     * @var SocialMediaRepository
     */
    protected $repository;

    /**
     * @var SocialMediaService
     */
    protected $socialMediaService;

    /**
     * SocialMediaController constructor.
     *
     * @param SocialMediaRepository $repository
     * @param SocialMediaService $socialMediaService
     */
    public function __construct(SocialMediaRepository $repository, SocialMediaService $socialMediaService)
    {
        $this->repository = $repository;
        $this->socialMediaService = $socialMediaService;
    }


    /**
     * @param Request $request
     */
    public function get(Request $request)
    {
        $redirect = $this->socialMediaService->socialLoginLinks($request);
        return $redirect;
    }

    public function fbCallBack(Request $request)
    {
        $redirect = $this->socialMediaService->fbCallBack($request);
        return $redirect;
    }

    public function fbPages(Request $request)
    {
        $redirect = $this->socialMediaService->fbPages($request);
        return $redirect;
    }

    public function twitCallBack(Request $request)
    {
        $redirect = $this->socialMediaService->twitCallBack($request);
        return $redirect;
    }

    public function pinCallBack(Request $request)
    {
        $redirect = $this->socialMediaService->pinCallBack($request);
        return $redirect;
    }
}
