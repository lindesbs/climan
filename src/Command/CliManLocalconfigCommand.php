<?php

namespace lindesbs\climan\Command;

use Contao\Config;
use Contao\StringUtil;
use Contao\System;
use Patchwork\Utf8;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;


class CliManLocalconfigCommand extends Command
{


    protected function configure(): void
    {
        $this->setName('climan:localconfig');
        $this->setDescription('handle localconfig.php items');

        $this->addArgument("key", InputArgument::OPTIONAL, 'show key');
        $this->addOption('installpassword', null, InputOption::VALUE_REQUIRED, 'change installpassword');
        $this->addOption('set', null, InputOption::VALUE_REQUIRED, 'set a value based on key');
        $this->addOption('remove', null, InputOption::VALUE_NONE, 'remove a key');

    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = System::getContainer();


        if ($input->getArgument("key"))
        {
            $config = \Config::getInstance();
            $key = $input->getArgument("key");
            $arrKeys = explode(",", $key);

            if (($input->getOption("set")) && (\count($arrKeys) == 1))
            {
                $value =$input->getOption("set");

                $config->add(sprintf('$GLOBALS[\'TL_CONFIG\'][\'%s\']',$key),$value);

                return;
            }


            if (($input->getOption("remove")) && (\count($arrKeys) == 1))
            {


                $config->remove($key);

                return;
            }


            $arrOutput = [];

            foreach ($arrKeys as $key)
            {
                $arrOutput[$key] = $config->get($key);
            }

            echo json_encode($arrOutput);
            return;
        }


        if ($input->getOption("installpassword"))
        {
            $password = $input->getOption("installpassword");

            $installTool = $container->get('contao.install_tool');
            $minlength = $installTool->getConfig('minPasswordLength');

            if (Utf8::strlen($password) < $minlength)
            {
                echo json_encode([
                    "error" => "password_too_short",
                    "min_length" => $minlength
                ]);

                exit - 1;
            }

            $installTool->persistConfig('installPassword', password_hash($password, PASSWORD_DEFAULT));
        }
    }


}
