<?php

namespace App\Form;

use App\Entity\Episode;
use App\Entity\Season;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('number', TextType::class, ['label' => 'Episode N°'])
            ->add('synopsis', TextType::class, ['label' => 'Synopsis'])
            ->add('season', EntityType::class, [
                'choice_label' => function (Season $season) {
                    return $season->getProgram()->getTitle() . ' ' . $season->getNumber();
                },
                'class' => Season::class,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
