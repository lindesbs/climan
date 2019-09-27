<?php

namespace lindesbs\climan\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\VarDumper\VarDumper;



class CliManCommand extends Command
{


    protected function configure(): void
    {
        $this->setName('climan:maintenance');
        $this->setDescription('change maintenance behaviour');

        $this->addOption('force', null, InputOption::VALUE_OPTIONAL, 'Force Migrations to be executed twice', false);

    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {


    }


}
