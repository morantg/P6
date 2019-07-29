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
     * @Route("/figure/{slug}-{id}", name="figure_show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Figure $figure, string $slug)
    {
        if ($figure->getSlug() !== $slug) {
            return $this->redirectToRoute('figure_show', [
                'id' => $figure->getId(),
                'slug' => $figure->getSlug()
            ], 301);
        }
        return $this->render('figure/show.html.twig', [
            'figure' => $figure
        ]);
    }
}
