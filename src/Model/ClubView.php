<?php

namespace App\Model;

use App\Entity\Club;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Serializer\XmlRoot("club")
 *
 * @Hateoas\Relation("self", href = "expr('/api/club/' ~ object.getUuid())")
 */
class ClubView
{
	private $uuid;
	private $name;
	private $logo;
	private $website_url;
	private $facebook_url;
	private $locations;

	public function __construct(Club $club, $locations)
	{
		$this->uuid = $club->getUuid();
		$this->name = $club->getName();
		$this->logo = $club->getLogo();
		$this->website_url = $club->getWebsiteUrl();
		$this->facebook_url = $club->getFacebookUrl();
		$this->locations = $locations;
	}

	public function getUuid(): ?string
	{
		return $this->uuid;
	}
	
	public function getName(): ?string
	{
		return $this->name;
	}

	public function getLogo(): ?string
	{
		return $this->logo;
	}

	public function getWebsiteUrl(): ?string
	{
		return $this->website_url;
	}

	public function getFacebookUrl(): ?string
	{
		return $this->facebook_url;
	}
	
}
