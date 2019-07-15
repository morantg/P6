<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Groupe;
use App\Form\FigureType;
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
    public function create(Request $request, ObjectManager $manager, UserInterface $user)
    {
        $figure = new Figure();
        
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            //ajout de l'utilisateur à la figure
            $figure->setUser($user);
            
            // ajout de la date courante à l'article
            $figure->setAjoutAt(new \Datetime);
            
            // upload de l'image à la une
            //$file = $figure->getImageUne();
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image_une']->getData();

            $fileName = md5(uniqid()).'.'.$uploadedFile->guessExtension();

            $uploadedFile->move(
                $this->getParameter('upload_directory'),
                $fileName
            );

            $figure->setImageUne($fileName);

            //upload des media
            for($i = 0; $i < 3; $i++){
                $media = new Media();
                $uploadedMedia = $form['media' . $i]->getData();
                if($uploadedMedia){
                    $fileMediaName = md5(uniqid()).'.'.$uploadedMedia->guessExtension();

                    $uploadedMedia->move(
                        $this->getParameter('upload_directory'),
                        $fileMediaName
                    );

                    $media->setUrl($fileMediaName);
                    $media->setFormat('image');
                    $figure->addMedium($media);
                }
            }
            
            // persist
            $manager->persist($figure);
            $manager->flush();
            
            //on redirige vers la figure crée
            return $this->redirectToRoute('home_show', ['id' => $figure->getId()]);
        }

        return $this->render('home/create.html.twig', [
            'formFigure' => $form->createView()
            ]);
    }

    /**
     * @Route("/figure/{id}/edit", name="figure_edit")
     */
    public function edit(Figure $figure, Request $request, ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            // ajout de la date courante à l'article
            $figure->setModifAt(new \Datetime);
            
            // upload de l'image à la une
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image_une']->getData();
            
            if($uploadedFile){
            
            $fileName = md5(uniqid()).'.'.$uploadedFile->guessExtension();

            $uploadedFile->move(
                $this->getParameter('upload_directory'),
                $fileName
            );
            $figure->setImageUne($fileName);
            }

            //upload des media
            $media = $figure->getMedia();
        
            foreach($media as $a => $medium){
                $uploadedMedia = $form['media' . $a]->getData();
                if($uploadedMedia){
                    $fileMediaName = md5(uniqid()).'.'.$uploadedMedia->guessExtension();

                    $uploadedMedia->move(
                        $this->getParameter('upload_directory'),
                        $fileMediaName
                    );

                    $medium->setUrl($fileMediaName);
                }
            }
            
            // persist
            $manager->persist($figure);
            $manager->flush();
            
            //on redirige vers la figure crée
            return $this->redirectToRoute('home_show', ['id' => $figure->getId()]);
        }

        return $this->render('home/edit.html.twig', [
            'formFigure' => $form->createView()
            ]);
    }

    /**
     * @Route("/figure/{id}", name="home_show")
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
