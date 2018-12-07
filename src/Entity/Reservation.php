<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    const PRICE_FISHING_12_H = 10;
    const PRICE_HOUSE_12_H = 10;
    const DISCOUNT = 0.9;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Range(
     *     min= "today",
     *     max = "today +30days",
     *     minMessage="Start Date can not be less than {{ limit }}",
     *     maxMessage="Start Date can not be more than {{ limit }}"
     * )
     */
    private $dateFrom;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Range(
     *     min= "today",
     *     max = "today +30days",
     *     minMessage="Reservation End date should be {{ limit }} or less.",
     *     maxMessage="Reservation End date should be {{ limit }} or more."
     * )
     * @Assert\GreaterThanOrEqual(
     *     propertyPath="dateFrom")
     */
    private $dateTo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $fishersNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $house;

    /**
     * @ORM\Column(type="integer")
     */
    private $hours;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paymentStatus = 'not paid';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $paymentId;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sectorName;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status = true;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeFrom;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeTo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTimeInterface $dateFrom): self
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    public function getDateTo(): ?\DateTimeInterface
    {
        return $this->dateTo;
    }

    public function setDateTo(\DateTimeInterface $dateTo): self
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFishersNumber(): ?int
    {
        return $this->fishersNumber;
    }

    public function setFishersNumber(int $fishersNumber): self
    {
        $this->fishersNumber = $fishersNumber;

        return $this;
    }

    public function getHouse(): ?bool
    {
        return $this->house;
    }

    public function setHouse(bool $house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(int $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getPaymentId(): ?int
    {
        return $this->paymentId;
    }

    public function setPaymentId(?int $paymentId): self
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSectorName(): ?string
    {
        return $this->sectorName;
    }

    public function setSectorName(string $sectorName): self
    {
        $this->sectorName = $sectorName;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTimeFrom(): ?int
    {
        return $this->timeFrom;
    }

    public function setTimeFrom(int $timeFrom): self
    {
        $this->timeFrom = $timeFrom;

        return $this;
    }

    public function getTimeTo(): ?int
    {
        return $this->timeTo;
    }

    public function setTimeTo(int $timeTo): self
    {
        $this->timeTo = $timeTo;

        return $this;
    }
}
