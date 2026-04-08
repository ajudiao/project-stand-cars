<?php
// app/Controllers/Public/HomeController.php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Repositories\CarRepository;

class HomeController extends Controller
{
    private CarRepository $carRepository;

    public function __construct()
    {
        $this->carRepository = new CarRepository();
    }

    public function index()
    {
        $heroCar = $this->carRepository->getCarroEmDestaque();
        $featuredCars = $this->carRepository->getFeaturedCars(3);

        $this->view('site/home', [
            'heroCar' => $heroCar,
            'featuredCars' => $featuredCars
        ]);
    }
}
