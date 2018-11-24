<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Validator\Constraints\Time;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(Request $request)
    {
        $dateFrom = new \DateTime('2018-11-27');
        $sectorName = "Trečias Sektorius";
        $amount = 30;
        $house = 0;
        $userId = 1;
        $hours = 36;

        if ($sectorName === "Trečias Sektorius") {
            $house = 1;
        }

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $timeFrom = $request->request->get('timeFrom');
            $reservation->setDateFrom($dateFrom->setTime($timeFrom, '00'));
            $dateTo = new \DateTime($request->request->get('reservation')['dateTo']);
            $timeTo = $request->request->get('timeTo');
            $reservation->setDateTo($dateTo->setTime($timeTo, '00'));
            $reservation->setSectorName($sectorName);
            $reservation->setHours($hours);
            $reservation->setAmount($amount);

            $reservation->setHouse($house);
            $reservation->setPaymentStatus('not paid');
            $reservation->setUserId($userId);
            $reservation->setStatus(1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Reservation successful!');

            return $this->redirectToRoute('home');
        }

        return $this->render('reservation/index.html.twig', [
            'form' => $form->createView(),
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'sectorNumber' => $sectorName,
            'hours' => $hours,
            'amount' => $amount,
            'house' => $house
        ]);
    }
}
