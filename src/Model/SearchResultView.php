<?php

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\XmlRoot("searchResult")
 */
class SearchResultView
{
    private $type;
    private $uuid;
    private $name;

    public function __construct($type, $uuid, $name)
    {
    	$this->type = $type;
    	$this->uuid = $uuid;
    	$this->name = $name;
    }

    public function getType(): ?string
    {
    	return $this->type;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

}
