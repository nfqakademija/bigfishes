<?php

namespace App\Controller;

use App\Service\ReservationService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Reservation;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @throws
     */
    public function index(ReservationService $reservationService)
    {
        $dateToday = new \DateTime('now');
        $reservations = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findBySectorsByDate($dateToday);

        $reservationsData = $reservationService->createReservationDataArray($reservations);

        return $this->render('home/index.html.twig', [
            'jsonContent' => $reservationsData
        ]);
    }
}
