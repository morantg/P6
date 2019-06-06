<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Groupe;
use App\Form\FigureType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function create(Request $request, ObjectManager $manager)
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {

           

            // ajout de la date courante à l'article
            $figure->setAjoutAt(new \Datetime);
            
            // upload de l'image à la une
            $file = $figure->getImageUne();
            
            
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('upload_directory'),
                $fileName
            );
            $figure->setImageUne($fileName);
            
            // upload media
           //$files= $figure->getMedia();
           
           $files = $request->files->get('figure')['media'];
           $media = $figure->getMedia();
           
           $tabUrl = array();
           $i = 0;
           
           foreach ($files as $fileMedia)
           {
                foreach ($fileMedia as $fileMedium){
                $fileNameMedia = $this->generateUniqueFileName().'.'.$fileMedium->guessExtension();

                $fileMedium->move(
                $this->getParameter('upload_directory'),
                $fileNameMedia
                );

                $tabUrl[$i] = $fileNameMedia;
                $i++; 
                }
            }

            $j=0;
            
            foreach ($media as $medium)
            {
                $medium->setUrl($tabUrl[$j]);  
                $j++;
                dump($medium);
            }

            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('home_show', ['id' => $figure->getId()]);
        }

        return $this->render('home/create.html.twig', [
            'formFigure' => $form->createView()
            ]);
    }

     /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
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
