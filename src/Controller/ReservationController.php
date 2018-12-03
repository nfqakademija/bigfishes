<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Validator\ValidateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class ReservationController extends AbstractController
{
    const SECTOR_NAME = "Trečias Sektorius";
    const AMOUNT = 30;
    const HOURS = 36;

    /**
     * @Route("/reservation", name="new_reservation")
     * @IsGranted("ROLE_USER")
     * @throws
     */
    public function new(Request $request, ValidateService $validator)
    {
        $dateFrom = new \DateTime($request->query->get('date'));
        $sectorNumber = $request->query->get('sector_name');
        $house = $sectorNumber === self::SECTOR_NAME ? true : false;

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($validator->isValidDate($dateFrom)) {

                $dateFrom = $dateFrom->setTime($form->getData()->getTimeFrom(), '00');
                $dateTo = $form->getData()->getDateTo()->setTime($form->get('timeTo')->getData(), '00');

                $isAvailableReservationRange = $this->getDoctrine()
                    ->getRepository(Reservation::class)
                    ->isAvailableReservationRange($sectorNumber, $dateFrom, $dateTo);

                if ($isAvailableReservationRange) {
                    $reservation->setDateFrom($dateFrom);
                    $reservation->setDateTo($dateTo);
                    $reservation->setSectorName($sectorNumber);
                    $reservation->setHours(self::HOURS);
                    $reservation->setAmount(self::AMOUNT);
                    $reservation->setHouse($house);
                    $reservation->setUserId($this->getUser()->getId());

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($reservation);
                    $entityManager->flush();

                    $this->addFlash('success', 'Reservation successful!');

                    return $this->redirectToRoute('home');
                } else {
                    $this->addFlash('warning', 'Date interval is not available');
                }
            } else {
                $this->addFlash('warning', 'Start date is not valid!');
            }
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
