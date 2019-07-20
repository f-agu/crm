<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserLessonSubscribeRepository")
 */
class UserLessonSubscribe
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userLessonSubscribes")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\ClubLesson", inversedBy="userLessonSubscribes")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $lesson;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $subscribe_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $unsubscribe_date;

	public function getId(): ?int
                           	{
                           		return $this->id;
                           	}

	public function getUser(): ?User
                           	{
                           		return $this->user;
                           	}

	public function setUser(?User $user): self
                           	{
                           		$this->user = $user;
                           		return $this;
                           	}

	public function getLesson(): ?ClubLesson
                           	{
                           		return $this->lesson;
                           	}

	public function setLesson(?ClubLesson $lesson): self
                           	{
                           		$this->lesson = $lesson;
                           		return $this;
                           	}

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getSubscribeDate(): ?\DateTimeInterface
    {
        return $this->subscribe_date;
    }

    public function setSubscribeDate(?\DateTimeInterface $subscribe_date): self
    {
        $this->subscribe_date = $subscribe_date;

        return $this;
    }

    public function getUnsubscribeDate(): ?\DateTimeInterface
    {
        return $this->unsubscribe_date;
    }

    public function setUnsubscribeDate(?\DateTimeInterface $unsubscribe_date): self
    {
        $this->unsubscribe_date = $unsubscribe_date;

        return $this;
    }
}
