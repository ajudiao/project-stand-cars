<?php
// app/Controllers/Public/HomeController.php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Repositories\CarRepository;
use App\Repositories\SiteSettingRepository;

class HomeController extends Controller
{
    private CarRepository $carRepository;
    private SiteSettingRepository $settingRepo;

    public function __construct()
    {
        $this->carRepository = new CarRepository();
        $this->settingRepo = new SiteSettingRepository();
    }

    public function index()
    {
        $heroCar = $this->carRepository->getCarroEmDestaque();
        $featuredCars = $this->carRepository->getFeaturedCars(3);
        $settings = $this->settingRepo->getAll();

        $this->view('site/home', [
            'heroCar' => $heroCar,
            'featuredCars' => $featuredCars,
            'settings' => $settings
        ]);
    }
}
