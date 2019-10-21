<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Groupe;
use App\Form\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
* 
         *
 * @var Figure|null $figure 
*/
        $figure = $options['data'] ?? null;
        $isEdit = $figure && $figure->getId();
        

        $imageConstraints = [
            new Image(
                [
                'maxSize' => '5M'
                ]
            )
        ];

        if (!$isEdit) {
            $imageConstraints[] = new NotNull(
                [
                'message' => 'Svp uploader une image',
                ]
            );
        }

        $builder
            ->add('nom')
            ->add('description')
            ->add(
                'image_une', Filetype::class, [
                'label' => 'Image à la une',
                'required' => false,
                'mapped' => false,
                'constraints' => $imageConstraints,
                ]
            )
            ->add(
                'groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom'
                ]
            )
            ->add(
                'media', CollectionType::class, [
                'entry_type' => MediaType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label' => false,
                ]
            )
            ->add(
                'video', CollectionType::class, [
                'entry_type' => MediaType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label' => false,
                ]
            )
            ->add(
                'save', SubmitType::class, [
                'label' => 'Enregistrer'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Figure::class,
            ]
        );
    }
}
