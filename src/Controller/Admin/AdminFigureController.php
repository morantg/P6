<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Groupe;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
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

class AdminFigureController extends AbstractController
{
    /**
     * @Route("/admin/figure/new", name="figure_create")
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
           
            $uploadedMedia = $form['media']->getData();

            foreach ($uploadedMedia as $uploadedMedium ){
                $uploadedMedia = $uploadedMedium->getFile();
                $fileMediaName = md5(uniqid()).'.'.$uploadedMedia->guessExtension();
                $uploadedMedia->move(
                    $this->getParameter('upload_directory'),
                    $fileMediaName
                );
                $uploadedMedium->setUrl($fileMediaName);
                $uploadedMedium->setFormat('image');
            }

            $uploadedVideos = $form['video']->getData();
           
            foreach ($uploadedVideos as $uploadedVideo ){
                $uploadedVideo->setUrl($uploadedVideo->getVideo());
                $uploadedVideo->setFormat('video');
                $figure->addMedium($uploadedVideo);
            }
            
            $manager->persist($figure);
            $manager->flush();
            $this->addFlash('success', 'Figure créé avec succès');
            
            return $this->redirectToRoute('figure_show', [
                'id' => $figure->getId(),
                'slug' => $figure->getSlug(),
                ]);
        }
        return $this->render('admin/figure/create.html.twig', [
            'formFigure' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/figure/{slug}-{id}", name="figure_edit", methods="GET|POST", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function edit(Figure $figure, Request $request, ObjectManager $manager, UserInterface $user = null, string $slug)
    {
        //vérification utilisateur
        if($user != $figure->getUser()){
            //$this->denyAccessUnlessGranted('ROLE_ADMIN');
            return $this->redirectToRoute('figure_show', [
                'id' => $figure->getId(),
                'slug' => $figure->getSlug()
                ]);
        }
        //vérification slug
        if ($figure->getSlug() !== $slug) {
            return $this->redirectToRoute('figure_edit', [
                'id' => $figure->getId(),
                'slug' => $figure->getSlug()
            ], 301);
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
            
            //upload des images
            foreach($media as $medium){
                $uploadedMedia = $medium->getFile();
                if($uploadedMedia){
                    $fileMediaName = md5(uniqid()).'.'.$uploadedMedia->guessExtension();

                    $uploadedMedia->move(
                        $this->getParameter('upload_directory'),
                        $fileMediaName
                    );
                    $medium->setUrl($fileMediaName);
                }
            }
            //upload des vidéos
            foreach($media as $medium){
                $uploadedVideo = $medium->getVideo();
                if($uploadedVideo){
                   $medium->setUrl($uploadedVideo);
                }
            }
            $manager->persist($figure);
            $manager->flush();
            $this->addFlash('success', 'Figure modifié avec succès');
            
            return $this->redirectToRoute('figure_show', [
                'id' => $figure->getId(),
                'slug' => $figure->getSlug()
            ], 301);
        }
        return $this->render('admin/figure/edit.html.twig', [
            'formFigure' => $form->createView(),
            'figure' => $figure,
        ]);
    }

    /**
     * @Route("/admin/figure/{id}", name="figure_delete", methods="DELETE")
     * @param Figure $figure
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Figure $figure, Request $request, ObjectManager $manager) {
        if ($this->isCsrfTokenValid('delete' . $figure->getId(), $request->get('_token'))) {
            
            $filename = $figure->getImageUne();
            $filesystem = new Filesystem();
            $filesystem->remove(
                $this->getParameter('upload_directory') . '/' . $filename
                );

           
            //dump($filesystem);
            //die();
             
            $media = $figure->getMedia();
            foreach($media as $medium){
            /*$filename = $medium->getUrl();
            $filesystem = new Filesystem();
            $filesystem->remove($filename);*/
            $manager->remove($medium);
            }
            $manager->remove($figure);
            $manager->flush();
            $this->addFlash('success', 'Figure supprimé avec succès');
        }
        return $this->redirectToRoute('figure_user');
    }

    /**
     * @Route("/admin/figure", name="figure_user")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(FigureRepository $repository, UserInterface $user)
    {
        $userId = $user->getId();
        $figures = $repository->findBy(
            [ 'user' => $userId ] 
        );
        return $this->render('admin/figure/index.html.twig', compact('figures'));
    }
}
