<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\CarRepository;
use App\Repositories\ClienteRepository;
use App\Repositories\VendaRepository;

class DashboardController extends Controller
{
    public function index()
    {
        $countCar = (new CarRepository())->getTheNumberOfVehicles();
        $countSeles = (new VendaRepository())->getTheNumbersSeles();
        $countClients = (new ClienteRepository())->getTheNumbersClients();

        echo $this->view('dashboard/index', [
            'countCar' => $countCar,
            'countSeles' => $countSeles,
            'countClients' => $countClients,
        ]);
    }

    public function configuracoes()
    {
        echo $this->view('dashboard/configuracoes', [
            'siteConfig' => 'Teste de configuração do site', // Exemplo de dado para a view
        ]);
    }
}