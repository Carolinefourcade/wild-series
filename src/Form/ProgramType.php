<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;

use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('summary', TextType::class, ['label' => 'Petit résumé'])
            ->add('poster', UrlType::class, ['label' => 'Poster'])
            ->add('year', IntegerType::class, ['label' => 'Année'])
            ->add('category', EntityType::class, [
                'choice_label' => 'name',
                'class'        => Category::class,
                'label'        => 'Catégorie'])
            ->add('actors', EntityType::class, [
                'class'         => Actor::class,
                'choice_label'  => 'name',
                'multiple'      => 'true',
                'expanded'      => 'true',
                'by_reference'  => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
