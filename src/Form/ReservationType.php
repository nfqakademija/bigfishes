<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Reservation name',
                    'attr' => array(
                        'placeholder' => 'Enter your Reservation name'
                    )
                )
            )
            ->add('timeFrom', ChoiceType::class, array(
                'choices'  => array('08:00' => '08', '20:00' => '20',),
                'expanded' => true,
                'data' => '08',
            ))
            ->add('dateTo', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datetimepicker',
                    'format' => 'Y-m-d',
                    'html5' => false,
                ]])
            ->add('timeTo', ChoiceType::class, array(
                'choices'  => array('08:00' => '08', '20:00' => '20',),
                'expanded' => true,
                'data' => '20',
            ))
            ->add('fishersNumber', ChoiceType::class, array(
                'choices'  => array(1 => 1, 2 => 2,),
                'expanded' => true,
                'data' => 1,
            ))
            ->add('userId', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
