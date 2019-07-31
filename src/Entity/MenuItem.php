<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuItemRepository")
 */
class MenuItem
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $code;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $priority;

	/**
	 * @ORM\Column(type="json")
	 */
	private $available_for_roles = [];

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getCode(): ?string
	{
		return $this->code;
	}

	public function setCode(string $code): self
	{
		$this->code = $code;

		return $this;
	}

	public function getPriority(): ?int
	{
		return $this->priority;
	}

	public function setPriority(int $priority): self
	{
		$this->priority = $priority;

		return $this;
	}

	public function getAvailableForRoles(): ?array
	{
		return $this->available_for_roles;
	}

	public function setAvailableForRoles(array $available_for_roles): self
	{
		$this->available_for_roles = $available_for_roles;

		return $this;
	}
	
	public function __toString()
	{
		if($this->getAvailableForRoles() != null) {
			return $this->getCode().' '.$this->getPriority().' ['.implode(', ', $this->getAvailableForRoles()).']';
		}
		return $this->getCode().' '.$this->getPriority();
	}
  
  
}
