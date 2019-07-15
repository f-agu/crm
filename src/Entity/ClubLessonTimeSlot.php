<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubLessonTimeSlotRepository")
 */
class ClubLessonTimeSlot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClubLesson", inversedBy="clubLessonTimeSlots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club_lesson;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClubTimeSlot", inversedBy="clubLessonTimeSlots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club_time_slot;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClubLesson(): ?ClubLesson
    {
        return $this->club_lesson;
    }

    public function setClubLesson(?ClubLesson $club_lesson): self
    {
        $this->club_lesson = $club_lesson;

        return $this;
    }

    public function getClubTimeSlot(): ?ClubTimeSlot
    {
        return $this->club_time_slot;
    }

    public function setClubTimeSlot(?ClubTimeSlot $club_time_slot): self
    {
        $this->club_time_slot = $club_time_slot;

        return $this;
    }
}
