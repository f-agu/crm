<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubTimeSlotRepository")
 */
class ClubTimeSlot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $day_of_week;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClubLessonTimeSlot", mappedBy="club_time_slot", orphanRemoval=true)
     */
    private $clubLessonTimeSlots;

    /**
     * @ORM\Column(type="time")
     */
    private $start_time;

    /**
     * @ORM\Column(type="time")
     */
    private $end_time;

    public function __construct()
    {
        $this->clubLessonTimeSlots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->day_of_week;
    }

    public function setDayOfWeek(string $day_of_week): self
    {
        $this->day_of_week = $day_of_week;

        return $this;
    }

    /**
     * @return Collection|ClubLessonTimeSlot[]
     */
    public function getClubLessonTimeSlots(): Collection
    {
        return $this->clubLessonTimeSlots;
    }

    public function addClubLessonTimeSlot(ClubLessonTimeSlot $clubLessonTimeSlot): self
    {
        if (!$this->clubLessonTimeSlots->contains($clubLessonTimeSlot)) {
            $this->clubLessonTimeSlots[] = $clubLessonTimeSlot;
            $clubLessonTimeSlot->setClubTimeSlot($this);
        }

        return $this;
    }

    public function removeClubLessonTimeSlot(ClubLessonTimeSlot $clubLessonTimeSlot): self
    {
        if ($this->clubLessonTimeSlots->contains($clubLessonTimeSlot)) {
            $this->clubLessonTimeSlots->removeElement($clubLessonTimeSlot);
            // set the owning side to null (unless already changed)
            if ($clubLessonTimeSlot->getClubTimeSlot() === $this) {
                $clubLessonTimeSlot->setClubTimeSlot(null);
            }
        }

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }
}
