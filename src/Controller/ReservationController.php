<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Translation\TranslatorInterface;

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
        if ($request->query->get('sector')) {
            $sector = $request->query->get('sector');
        } else {
            $this->addFlash('warning', 'Reservation Sector not selected');
            return $this->redirectToRoute('home');
        }

        $isSectorValid = $reservationService->isSectorValid($sector);
        if ($isSectorValid) {
            $sectorNumber = $reservationService->sectorKeyToName($sector);
        } else {
            $sectorNumber = $sector;
        }

        $house = $sectorNumber === self::SECTOR_NUMBER ? true : false;
        try {
            $dateFrom = new \DateTime($request->query->get('date', 'now'));
        } catch (\Exception $e) {
            $dateFrom = new \DateTime('now');
        }

        $reservation = new Reservation();
        $reservation->setDateFrom($dateFrom);
        $reservation->setHouse($house);

        $availableDateTo = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAvailableDateTo($sectorNumber, $dateFrom);


        $isFromAvailableFrom8 = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->isAvailableDateFrom($sectorNumber, $dateFrom);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        $default_date_to = $dateFrom;

        if ($form->isSubmitted() && $form->isValid()) {
            $dateFrom = $form->getData()->getDateFrom()->setTime($form->get('timeFrom')->getData(), '00');
            $dateTo = $form->getData()->getDateTo()->setTime($form->get('timeTo')->getData(), '00');
            $default_date_to = $dateTo;


            $isAvailableDateFrom = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->isAvailableDateFrom($sectorNumber, $dateFrom);


            if ($isAvailableDateFrom) {
                $isAvailableDateTo = $this->getDoctrine()
                    ->getRepository(Reservation::class)
                    ->isAvailableDateTo($sectorNumber, $dateTo, $dateFrom);

                if ($isAvailableDateTo) {
                    if ($isSectorValid) {
                        $reservation->setSectorName($sectorNumber);
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

                            $reservation->setUser($this->getUser());

                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($reservation);
                            $entityManager->flush();

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
                        $this->addFlash(
                            'warning',
                            'Sector does not exist'
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
            'isFromAvailableFrom8' => $isFromAvailableFrom8,
            'sector_name' => $reservationService->sectorKeyToName($sector),
            'default_date_to' => $default_date_to
        ]);
    }

    /**
     * @Route("/myReservations", name="my_reservations")
     * @IsGranted("ROLE_USER")
     * @throws
     */

    public function myReservations(ReservationService $reservationService)
    {
        $userReservations = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findByUser($this->getUser()->getId());

        $userData = $reservationService->createUserReservationDataArray($userReservations);

        return $this->render('reservation/myReservations.html.twig', [
            'userData' => $userData,
            'username' => $this->getUser()->getName(),
            'email' => $this->getUser()->getEmail()
        ]);
    }

    /**
     * @Route("/reservation/confirm", name="confirm_reservation")
     * @IsGranted("ROLE_USER")
     */
    public function sendEmail(\Swift_Mailer $mailer, TranslatorInterface $translator, Request $request)
    {
        $reservationId = $request->request->get('id');
        $reservation = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findOneByIdField($reservationId);

        $message = (new \Swift_Message($translator->trans('Reservation Confirmation')))
            ->setFrom('bigfisheslt@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/reservation.html.twig',
                    array('name' => $this->getUser()->getName(),
                        'reservation' => $reservation
                    )
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/reservation/payment", name="payment_reservation")
     * @IsGranted("ROLE_USER")
     */
    public function payment(Request $request)
    {
        $reservationId = $request->request->get('id');
        $reservation = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findOneByIdField($reservationId);
        $this->addFlash('success', 'Reservation made successfully');

        return $this->render('reservation/payment.html.twig', [
            'reservation' => $reservation,
            'userEmail' => $this->getUser()->getEmail()
        ]);
    }
}
