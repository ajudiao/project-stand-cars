<?php
// app/Controllers/Public/HomeController.php

namespace App\Controllers\Public;
use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('site/home', [
            'message' => 'Olá Mundo com Twig'
        ]);
    }
}