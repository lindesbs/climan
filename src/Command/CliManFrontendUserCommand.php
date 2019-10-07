<?php

namespace lindesbs\climan\Command;

use Contao\System;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CliManFrontendUserCommand extends Command
{


    protected function configure(): void
    {
        $this->setName('climan:frontenduser');
        $this->setDescription('stuff for frontenduser');

        $this->addArgument("username", InputArgument::REQUIRED, 'show user');

        $this->addOption('password', null, InputOption::VALUE_REQUIRED, 'change users password');
        $this->addOption('disable', null, InputOption::VALUE_NONE, 'disable user');
        $this->addOption('enable', null, InputOption::VALUE_NONE, 'enable user');
        $this->addOption('lock', null, InputOption::VALUE_NONE, 'lock user');
        $this->addOption('unlock', null, InputOption::VALUE_NONE, 'unlock user');

    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = \Contao\System::getContainer();
        $container->get('contao.framework')->initialize(true);

        $username = $input->getArgument("username");


        $objFeUser = \FrontendUser::getInstance();

        $feUser = $objFeUser->findBy("username", $username);


        if ($feUser)
        {

            if ($input->getOption("lock"))
            {

                $objFeUser->loginCount = 0;
                $objFeUser->locked = time() + 300;
                $objFeUser->save();

                return;
            }

            if ($input->getOption("unlock"))
            {
                $objFeUser->loginCount = 3;
                $objFeUser->locked = 0;
                $objFeUser->save();

                return;
            }

            if ($input->getOption("disable"))
            {
                $objFeUser->disable = 1;
                $objFeUser->save();

                return;
            }

            if ($input->getOption("enable"))
            {
                $objFeUser->disable = 0;
                $objFeUser->save();

                return;
            }


            if ($input->getOption("password"))
            {
                $objFeUser->setPassword(password_hash($input->getOption("password"), PASSWORD_DEFAULT));
                $objFeUser->save();

                return;
            }


            echo json_encode($objFeUser->getData());
        }


    }


}
