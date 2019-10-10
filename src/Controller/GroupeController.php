<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Groupe;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    /**
     * @Route("/groupe/{id}", name="groupe_index")
     */
    public function index(PaginatorInterface $paginator, Groupe $groupe, Request $request)
    {
        
        $figures = $paginator->paginate(
            $groupe->getFigures(),
            $request->query->getInt('page',1),
            5
        );
        
        return $this->render('groupe.html.twig', [
            'controller_name' => 'GroupeController',
            'figures' => $figures,
            'groupe' => $groupe
        ]);
    }
}
