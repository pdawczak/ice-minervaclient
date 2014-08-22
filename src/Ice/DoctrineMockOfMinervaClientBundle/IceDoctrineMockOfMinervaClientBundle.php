<?php

namespace Ice\DoctrineMockOfMinervaClientBundle;

use Ice\DoctrineMockOfMinervaClientBundle\CompilerPass\ReplaceServicePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IceDoctrineMockOfMinervaClientBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ReplaceServicePass());
        parent::build($container);
    }
}
