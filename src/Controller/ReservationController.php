<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Services\HoursCalculation;
use App\Services\PricesCalculation;
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
    public function new(Request $request, PricesCalculation $prices, HoursCalculation $hours)
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

        //Kokia galima artimiausia DateTo data
        $availableDateTo = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAvailableDateTo($sectorNumber, $dateFrom);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        $dateTo = $dateFrom;

        if ($form->isSubmitted() && $form->isValid()) {
            $dateFrom = $form->getData()->getDateFrom()->setTime($form->get('timeFrom')->getData(), '00');
            $dateTo = $form->getData()->getDateTo()->setTime($form->get('timeTo')->getData(), '00');

            //Ar pradžios data yra laisva
            $isAvailableDateFrom = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->isAvailableDateFrom($sectorNumber, $dateFrom);

            if ($isAvailableDateFrom) {
                //Ar pabaigos data yra laisva
                $isAvailableDateTo = $this->getDoctrine()
                    ->getRepository(Reservation::class)
                    ->isAvailableDateTo($sectorNumber, $dateTo, $dateFrom);

                if ($isAvailableDateTo) {
                    //Ar tai savaitgalis ir laikas 8:00
                    $isDateFromTimeFrom08 = $this->getDoctrine()
                        ->getRepository(Reservation::class)
                        ->isTimeFrom08($dateFrom);
                    $isDateToTimeFrom08 = $this->getDoctrine()
                        ->getRepository(Reservation::class)
                        ->isTimeFrom08($dateTo);

                    if (!$isDateFromTimeFrom08 && !$isDateToTimeFrom08) {
                        $totalHours = $hours->hoursTotal($dateFrom, $dateTo);
                        $fishersNumber = $reservation->getFishersNumber();
                        $fishingPrice = $prices->fishingPriceCalculation($fishersNumber, $totalHours);
                        $housePrice = $prices->housePriceCalculation($totalHours);

                        $totalPrice = $sectorNumber === self::SECTOR_NUMBER ?
                            $prices->totalPriceCalculation($fishingPrice, $housePrice) : $fishingPrice;

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
}
