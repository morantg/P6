<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Groupe;
use App\Form\FigureType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Figure::class);
        $figures = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page',1),
            12
        );
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'figures' => $figures
        ]);
    }
}
