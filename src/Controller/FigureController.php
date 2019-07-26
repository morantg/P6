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

class FigureController extends AbstractController
{
    /**
     * @Route("/figure/new", name="figure_create")
     */
    public function create(Request $request, ObjectManager $manager, UserInterface $user)
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $figure->setUser($user);
            $figure->setAjoutAt(new \Datetime);
            // upload de l'image à la une
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
            $manager->persist($figure);
            $manager->flush();
            
            return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);
        }
        return $this->render('figure/create.html.twig', [
            'formFigure' => $form->createView()
        ]);
    }

    /**
     * @Route("/figure/{id}/edit", name="figure_edit")
     */
    public function edit(Figure $figure, Request $request, ObjectManager $manager, UserInterface $user = null)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if($user != $figure->getUser()){
            //$this->denyAccessUnlessGranted('ROLE_ADMIN');
            return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);
        }
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
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
            $manager->persist($figure);
            $manager->flush();
            
            return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);
        }
        return $this->render('figure/edit.html.twig', [
            'formFigure' => $form->createView()
        ]);
    }

    /**
     * @Route("/figure/{id}", name="figure_show")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Figure::class);
        $figure = $repo->find($id);
       
        return $this->render('figure/show.html.twig', [
            'figure' => $figure
        ]);
    }
}
