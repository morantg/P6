<?php

namespace App\Controller;

use App\Entity\Figure;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Figure::class);

        $figures = $repo->findAll();
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'figures' => $figures
        ]);
    }

    /**
     * @Route("/{id}", name="home_show")
     */
    public function show($id){
        $repo = $this->getDoctrine()->getRepository(Figure::class);
        $figure = $repo->find($id);
        return $this->render('home/show.html.twig', [
            'figure' => $figure
        ]);
    }



}
