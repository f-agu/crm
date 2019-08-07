<?php
namespace App\Media;

use Symfony\Component\Finder\SplFileInfo;

class Media
{
	private $file; // SplFileInfo

	public function __construct(SplFileInfo $file = null)
	{
		$this->file = $file;
	}

	public function isFound(): bool
	{
		return $this->file != null;
	}

	public function getFile(): ?\SplFileInfo
	{
		return $this->file;
	}
}

