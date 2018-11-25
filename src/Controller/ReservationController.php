<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    const SECTOR_NAME = "Trečias Sektorius";
    const AMOUNT = 30;
    const HOURS = 36;
    const USER_ID = 5;
    const STATUS = true;
    /**
     * @Route("/reservation", name="reservation_create")
     */
    public function create(Request $request)
    {
        $dateFrom = new \DateTime('2018-11-27');
        $house = self::SECTOR_NAME === "Trečias Sektorius" ? true : false;

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $timeFrom = $request->request->get('timeFrom');
            $reservation->setDateFrom($dateFrom->setTime($timeFrom, '00'));

            $dateTo = new \DateTime($request->request->get('reservation')['dateTo']);
            $timeTo = $request->request->get('timeTo');
            $reservation->setDateTo($dateTo->setTime($timeTo, '00'));

            $reservation->setSectorName(self::SECTOR_NAME);
            $reservation->setHours(self::HOURS);
            $reservation->setAmount(self::AMOUNT);
            $reservation->setHouse($house);
            $reservation->setPaymentStatus('not paid');
            $reservation->setUserId(self::USER_ID);
            $reservation->setStatus(self::STATUS);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Reservation successful!');

            return $this->redirectToRoute('home');
        }

        return $this->render('reservation/create.html.twig', [
            'form' => $form->createView(),
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'sectorNumber' => self::SECTOR_NAME,
            'hours' => self::HOURS,
            'amount' => self::AMOUNT,
            'house' => $house
        ]);
    }
}
