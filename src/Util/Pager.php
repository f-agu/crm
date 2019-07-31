<?php
namespace App\Util;

use Symfony\Component\HttpFoundation\Request;

class Pager
{

	private $page;
	private $elementbypage;
	private $offset;
	
	public function __construct(Request $request)
	{
		$this->page = intval($request->query->get('page', 0));
		$this->elementbypage = intval($request->query->get('n', 20));
		$this->offset = $this->page * $this->elementbypage;
	}
	
	public function isValid(): ?bool
	{
		return $this->page >= 0 && $this->elementbypage >= 0;
	}
	
	public function getPage(): ?int
	{
		return $this->page;
	}
	
	public function getElementByPage(): ?int
	{
		return $this->elementbypage;
	}
	
	public function getOffset(): ?int
	{
		return $this->offset;
	}
	
}

