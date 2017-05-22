<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DatePickerType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        //On pré-paramètre le DateTimeType pour qu'il fonctionne avec DateTimePicker
        $resolver->setDefaults(array('required' => false,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy HH:mm',
            'attr' => array('class' => 'datetimepicker')
        ));
    }

    public function getParent() // On utilise l'héritage de formulaire
    {
        return DateTimeType::class;
    }
}
