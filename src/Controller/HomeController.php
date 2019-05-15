<?php

namespace App\Controller;

use App\Entity\Figure;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/new", name="figure_create")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $figure = new Figure();

        $form = $this->createFormBuilder($figure)
                     ->add('nom')
                     ->add('description')
                     ->add('groupe')
                     ->add('image_une')
                     ->add('save', SubmitType::class, [
                         'label' => 'Enregistrer'
                     ])
                     ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $figure->setAjoutAt(new \Datetime);

            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('home_show', ['id' => $figure->getId()]);
        }

        return $this->render('home/create.html.twig', [
            'formFigure' => $form->createView()
            ]);
    }

    /**
     * @Route("/{id}", name="home_show")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Figure::class);
        
        $figure = $repo->find($id);
        
        return $this->render('home/show.html.twig', [
            'figure' => $figure
        ]);
    }




}
