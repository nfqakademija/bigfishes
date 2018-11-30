<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class ReservationController extends AbstractController
{
    const SECTOR_NAME = "Trecias sektorius";
    const AMOUNT = 30;
    const HOURS = 36;
    const USER_ID = 5;

    /**
     * @Route("/reservation", name="new_reservation")
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request)
    {
        $dateFrom = new \DateTime($request->query->get('date'));
        $sectorNumber = $request->query->get('sector_name');
        $house = $sectorNumber === self::SECTOR_NAME ? true : false;


        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setDateFrom($dateFrom->setTime($form->getData()->getTimeFrom(), '00'));
            $reservation->setDateTo($form->getData()->getDateTo()->setTime($form->get('timeTo')->getData(), '00'));
            $reservation->setSectorName($sectorNumber);
            $reservation->setHours(self::HOURS);
            $reservation->setAmount(self::AMOUNT);
            $reservation->setHouse($house);
            $reservation->setUserId(self::USER_ID);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Reservation successful!');

            return $this->redirectToRoute('home');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'sectorNumber' => $sectorNumber,
            'hours' => self::HOURS,
            'amount' => self::AMOUNT,
            'house' => $house
        ]);
    }
}
