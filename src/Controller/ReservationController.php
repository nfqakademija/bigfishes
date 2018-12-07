<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Services\Hours;
use App\Services\Prices;
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

    /**
     * @Route("/reservation", name="new_reservation")
     * @IsGranted("ROLE_USER")
     * @throws
     */
    public function new(Request $request, Prices $prices, Hours $hours)
    {
        $sectorNumber = $request->query->get('sector_name');
        $house = $sectorNumber === self::SECTOR_NAME ? true : false;

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
                    $totalHours = $hours->hoursTotal($dateFrom, $dateTo);
                    $fishersNumber = $reservation->getFishersNumber();
                    $fishingPrice = $prices->fishingPriceCalculation($fishersNumber, $totalHours);
                    $housePrice = $prices->housePriceCalculation($totalHours);

                    if ($sectorNumber === self::SECTOR_NAME) {
                        $totalPrice = $prices->totalPriceCalculation($fishingPrice, $housePrice);
                    } else {
                        $totalPrice = $fishingPrice;
                    }

                    $reservation->setDateFrom($dateFrom);
                    $reservation->setDateTo($dateTo);
                    $reservation->setSectorName($sectorNumber);
                    $reservation->setHours($totalHours);
                    $reservation->setAmount($totalPrice);
                    $reservation->setUserId($this->getUser()->getId());

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($reservation);
                    $entityManager->flush();

                    $this->addFlash('success', 'Reservation successful!');

                    return $this->render('reservation/confirm.html.twig', [
                        'dateFrom' => $dateFrom->format('Y-m-d H:i'),
                        'dateTo' => $dateTo->format('Y-m-d H:i'),
                        'sectorNumber' => $sectorNumber,
                        'house' => $house,
                        'totalHours' => $totalHours,
                        'fishingPrice' => $fishingPrice,
                        'housePrice' => $housePrice,
                        'totalPrice' => $totalPrice,
                        'fishersNumber' => $fishersNumber,
                    ]);
                } else {
                    $this->addFlash('warning', 'Reservation End Date is not available');
                }
            } else {
                $this->addFlash('warning', 'Reservation Start Date is not available');
            }
        }
        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'sectorNumber' => $sectorNumber,
            'house' => $house,
            'availableDateTo' => $availableDateTo->format('Y-m-d')
        ]);
    }
}
