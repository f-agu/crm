<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubLessonRepository")
 */
class ClubLesson
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClubLocation", inversedBy="clubLessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club_location;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="clubLessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\Column(type="integer")
     */
    private $point;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $discipline;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $age_level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClubLocation(): ?ClubLocation
    {
        return $this->club_location;
    }

    public function setClubLocation(?ClubLocation $club_location): self
    {
        $this->club_location = $club_location;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getDiscipline(): ?string
    {
        return $this->discipline;
    }

    public function setDiscipline(string $discipline): self
    {
        $this->discipline = $discipline;

        return $this;
    }

    public function getAgeLevel(): ?string
    {
        return $this->age_level;
    }

    public function setAgeLevel(?string $age_level): self
    {
        $this->age_level = $age_level;

        return $this;
    }
}
