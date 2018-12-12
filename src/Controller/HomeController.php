<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Reservation;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $dateFrom = new \DateTime('now');
        $reservationData = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findBySectorsByDate($dateFrom);


        return $this->render('home/index.html.twig', [
            'jsonContent' => $reservationData
        ]);
    }
}
