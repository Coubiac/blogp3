<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TinyMCEType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('class' => 'tinymce') // On ajoute la classe CSS
        ));
    }

    public function getParent() // On utilise l'héritage de formulaire
    {
        return TextareaType::class;
    }

}
