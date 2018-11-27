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
            ))
            //->add('dateFrom', DateType::class)
            ->add('dateTo', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'd-inline-block datetimepicker',
                    'data-provide' => 'datetimepicker',
                    'format' => 'Y-m-d',
                    'html5' => false,
                ]])
            ->add('fishersNumber', ChoiceType::class, array(
                'choices'  => array(1 => 1, 2 => 2,),
                'expanded' => true,
                'data' => 1,
                'attr' => [
                    'class' => 'd-inline-block',
                ]
            ))
            //->add('hours')
            //->add('amount')
            //->add('sectorName')
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
