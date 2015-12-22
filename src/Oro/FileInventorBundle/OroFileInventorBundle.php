<?php

namespace Oro\FileInventorBundle;

use Oro\FileInventorBundle\DependencyInjection\SearchEngineCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OroFileInventorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SearchEngineCompilerPass());
    }
}
