<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputArgument;

class SwaggerCommand extends Command
{
	// the name of the command (the part after "bin/console")
	protected static $defaultName = 'swagger:generate';

	protected function configure()
	{
		$this
			->setDescription('Dump the dabatase')
			->setHelp('This command dumps the database');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$openapi = \OpenApi\scan('D:\cenaclerm\git\crm\src/Controller/Api');
		$output->writeln('toto');
		
	}

}

