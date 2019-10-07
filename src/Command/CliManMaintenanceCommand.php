<?php

namespace lindesbs\climan\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;


class CliManMaintenanceCommand extends Command
{


    protected function configure(): void
    {
        $this->setName('climan:maintenance');
        $this->setDescription('change maintenance behaviour');

        $this->addOption('enable', null, InputOption::VALUE_NONE, 'change maintenance option');
        $this->addOption('disable', null, InputOption::VALUE_NONE, 'change maintenance option');

    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rootDir = \Contao\System::getContainer()->getParameter('kernel.project_dir');
        $filesystem = new Filesystem();
        $strLock = $rootDir . '/var/maintenance_lock';


        $bMaintenanceLockExists = $filesystem->exists($strLock);


        if ($input->getOption('enable'))
        {

            $filesystem->touch($strLock);
            $bMaintenanceLockExists = true;
        }

        if ($input->getOption('disable'))
        {
            $filesystem->remove([$strLock]);
            $bMaintenanceLockExists = false;
        }


        echo json_encode(['active' => $bMaintenanceLockExists]);

    }


}
