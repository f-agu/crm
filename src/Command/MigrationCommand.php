<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputArgument;
use App\Entity\Club;
use Symfony\Component\HttpKernel\KernelInterface;
use Psr\Log\LoggerInterface;
use App\Media\MediaManager;

class MigrationCommand extends Command
{
	protected static $defaultName = 'crm:migration';

	private $doctrine;
	private $mediaManager;

	public function __construct(ManagerRegistry $doctrine, KernelInterface $appKernel, LoggerInterface $logger)
	{
		parent::__construct();
		$this->doctrine = $doctrine;
		$this->mediaManager = new MediaManager($appKernel, $logger);
	}

	protected function configure()
	{
		$this->addArgument('domain_name', InputArgument::REQUIRED, 'Domain name');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$domain = $input->getArgument('domain_name');
		foreach($this->doctrine->getManager()->getRepository(Club::class)->findAll() as $club)
		{
			$url = 'https://'.$domain.'/param_clubs/logo_club/'.$club->getLogo();
			echo 'Download logo from '.$url.PHP_EOL;
			$this->mediaManager->downloadAndSave($url, 'club', $club->getUuid());
		}

	}

}

