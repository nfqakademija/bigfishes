<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom')
            ->add('dateTo')
            ->add('name')
            ->add('fishersNumber')
            ->add('house')
            ->add('hours')
            ->add('paymentStatus')
            ->add('paymentId')
            ->add('amount')
            ->add('sectorName')
            ->add('userId')
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
