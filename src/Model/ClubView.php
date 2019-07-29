<?php

namespace App\Entity;

class ClubView
{
    private $uuid;
    private $name;
    private $logo;
    private $website_url;
    private $facebook_url;
    private $locations;

    public function __construct(Club $club)
    {
        $this->uuid = $club->getUuid();
        $this->name = $club->getName();
        $this->logo = $club->getLogo();
        $this->website_url = $club->getWebsiteUrl();
        $this->facebook_url = $club->getFacebookUrl();
        //$this->locations = $club->getUuid();
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

    public function jsonSerialize()
    {
        return
        [
            'uuid'   => $this->getUuid(),
            'name' => $this->getName(),
            'logo' => $this->getLogo(),
            'website_url' => $this->getWebsiteUrl(),
            'facebook_url' => $this->getFacebookUrl(),
            // TODO locations
        ];
    }
    
}
