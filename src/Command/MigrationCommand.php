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
use Symfony\Component\Console\Input\InputOption;

/**
 * php bin/console crm:migration --domainname=<domain> --dump=dump_src.sql
 * @author f.agu
 */
class MigrationCommand extends Command
{
	protected static $defaultName = 'crm:migration';

	private $doctrine;
	private $mediaManager;
	private $projectDir;

	public function __construct(ManagerRegistry $doctrine, KernelInterface $appKernel, LoggerInterface $logger)
	{
		parent::__construct();
		$this->doctrine = $doctrine;
		$this->mediaManager = new MediaManager($appKernel, $logger);
		$this->projectDir = $appKernel->getProjectDir();
	}

	protected function configure()
	{
		$this->addOption('domainname', 'd', InputOption::VALUE_REQUIRED, 'Domain name to download club logo', null);
		$this->addOption('dump', null, InputOption::VALUE_REQUIRED, 'Dump file from legacy database', null);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$domain = $input->getOption('domainname');
		$srcdump = $input->getOption('dump');
		if(! file_exists($srcdump)) {
			throw new \Exception('File not found: '.$srcdump);
		}

		$this->importDump($srcdump);
		$this->importDump($this->projectDir.DIRECTORY_SEPARATOR.'doc'.DIRECTORY_SEPARATOR.'migration.sql');

		$this->downloadClubLobo($domain);
	}


	private function importDump($dumpfile)
	{
		$conn = $this->doctrine->getConnection();

		$cmd = sprintf('mysql -u %s --password=%s %s < %s',
			$conn->getUsername(),
			$conn->getPassword(),
			$conn->getDatabase(),
			$dumpfile
			);
		//$output->writeln($cmd);
		//echo $cmd.PHP_EOL;
		echo 'Import DB dump '.$dumpfile.PHP_EOL;

		exec($cmd);
	}


	private function downloadClubLobo($domain)
	{
		foreach($this->doctrine->getManager()->getRepository(Club::class)->findAll() as $club)
		{
			$url = 'https://'.$domain.'/param_clubs/logo_club/'.$club->getLogo();
			echo 'Download logo from '.$url.PHP_EOL;
			$this->mediaManager->downloadAndSave($url, 'club', $club->getUuid());
		}
	}

}

