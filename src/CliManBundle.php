<?php


namespace lindesbs\climan;

use lindesbs\climan\DependencyInjection\CliManExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CliManBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new CliManExtension();
    }

        /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

    }

}
