<?php

namespace App\Controllers\Public;
use App\Core\Controller;
use App\Repositories\NewsRepository;

class NoticiasController extends Controller
{
    private NewsRepository $newsRepo;

    public function __construct()
    {
        $this->newsRepo = new NewsRepository();
    }

    public function index()
    {
        $noticias = $this->newsRepo->getAllPublished();

        $this->view('site/noticias', [
            'noticias' => $noticias
        ]);
    }

    public function show($slug)
    {
        $noticia = $this->newsRepo->getBySlug($slug);

        if (!$noticia) {
            $this->view('errors/404');
            return;
        }

        $this->view('site/noticia-detalhes', [
            'noticia' => $noticia
        ]);
    }
}