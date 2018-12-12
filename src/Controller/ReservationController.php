<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class ReservationController extends AbstractController
{
    const SECTOR_NUMBER = "Trečias Sektorius";

    /**
     * @Route("/reservation", name="new_reservation")
     * @IsGranted("ROLE_USER")
     * @throws
     */
    public function new(Request $request, ReservationService $reservationService)
    {
        $sectorNumber = $request->query->get('sector_name');
        $house = $sectorNumber === self::SECTOR_NUMBER ? true : false;

        $reservation = new Reservation();

        try {
            $dateFrom = new \DateTime($request->query->get('date', 'now'));
        } catch (\Exception $e) {
            $dateFrom = new \DateTime('now');
        }

        $reservation->setDateFrom($dateFrom);
        $reservation->setSectorName($sectorNumber);
        $reservation->setHouse($house);

        //Looking for nearest available DateTo
        $availableDateTo = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAvailableDateTo($sectorNumber, $dateFrom);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        $dateTo = $dateFrom;

        if ($form->isSubmitted() && $form->isValid()) {
            $dateFrom = $form->getData()->getDateFrom()->setTime($form->get('timeFrom')->getData(), '00');
            $dateTo = $form->getData()->getDateTo()->setTime($form->get('timeTo')->getData(), '00');

            $isAvailableDateFrom = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->isAvailableDateFrom($sectorNumber, $dateFrom);

            if ($isAvailableDateFrom) {
                $isAvailableDateTo = $this->getDoctrine()
                    ->getRepository(Reservation::class)
                    ->isAvailableDateTo($sectorNumber, $dateTo, $dateFrom);

                if ($isAvailableDateTo) {
                    if (!$reservationService->isTimeFrom08($dateFrom) &&
                        !$reservationService->isTimeFrom08($dateTo)) {
                        $totalHours = $reservationService->hoursTotal($dateFrom, $dateTo);
                        $fishersNumber = $reservation->getFishersNumber();
                        $fishingPrice = $reservationService->fishingPriceCalculation($fishersNumber, $totalHours);
                        $housePrice = $reservationService->housePriceCalculation($totalHours);

                        $totalPrice = $sectorNumber === self::SECTOR_NUMBER ?
                            $reservationService->totalPriceCalculation($fishingPrice, $housePrice) : $fishingPrice;

                        $reservation->setDateFrom($dateFrom);
                        $reservation->setDateTo($dateTo);
                        $reservation->setHours($totalHours);
                        $reservation->setAmount($totalPrice);
                        $reservation->setUserId($this->getUser()->getId());

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($reservation);
                        $entityManager->flush();

                        $this->addFlash('success', 'Reservation date confirmed!');

                        return $this->render('reservation/confirm.html.twig', [
                            'data' => $form->getData(),
                            'fishingPrice' => $fishingPrice,
                            'housePrice' => $housePrice,
                        ]);
                    } else {
                        $this->addFlash(
                            'warning',
                            'The Reservation time in weekend available from 20:00 to 20:00'
                        );
                    }
                } else {
                    $this->addFlash('warning', 'Reservation End Date is not available');
                }
            } else {
                $this->addFlash('warning', 'Reservation Start Date is not available');
            }
        }
        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'data' => $form->getData(),
            'availableDateTo' => $availableDateTo,
            'dateTo' => $dateTo
        ]);
    }

    /**
     * @Route("/myReservations", name="user reservations")
     * @IsGranted("ROLE_USER")
     * @throws
     */

    public function index()
    {
        return $this->render('reservation/myReservations.html.twig');
    }
}
