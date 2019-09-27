<?php

namespace lindesbs\climan\Command;

use Contao\System;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;


class CliManDBCommand extends Command
{


    protected function configure(): void
    {
        $this->setName('climan:db');
        $this->setDescription('db stuff');

        $this->addArgument("generate");
        $this->addOption('gzip', null, InputOption::VALUE_NONE, 'gzip');
        $this->addOption('gi', null, InputOption::VALUE_NONE, 'add generation info to filename');

    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if ($input->getArgument("generate"))
        {

            $param = System::getContainer()->get('database_connection')->getParams();


            $db = new \mysqli($param['host'], $param['user'], $param['password'], $param['dbname'], $param['port']);
            $dump = new \MySQLDump($db);

            $strFilename = "export.sql";


            if ($input->getOption("gi"))
            {
                $strFilename = sprintf("export_%s.sql", date("Y-m-d_H-i-s"));
            }

            if ($input->getOption("gzip"))
            {
                $strFilename.=".gz";
            }

            $dump->save(TL_ROOT . '/'.$strFilename);

            return;
        }

    }


}
