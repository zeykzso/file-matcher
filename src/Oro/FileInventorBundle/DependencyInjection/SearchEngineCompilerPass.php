<?php

namespace Oro\FileInventorBundle\DependencyInjection;

use Oro\FileInventorBundle\Inventor\FileSearchEngineInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SearchEngineCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(Configuration::INVENTOR_SERVICE)) {
            throw new \RuntimeException('There main inventor service is missing');
        }

        $inventorService = $container->findDefinition((Configuration::INVENTOR_SERVICE));
        $searchEngines = $container->findTaggedServiceIds(Configuration::SEARCH_ENGINE_TAG);

        // register search engines on main inventor service
        foreach ($searchEngines as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['alias'])) {
                    throw new \RuntimeException(sprintf('You must define an alias for the "%s" search engine', $serviceId));
                }
                $inventorService->addMethodCall(
                    'addSearchEngine',
                    [new Reference($serviceId), $attributes['alias']]
                );
            }
        }

        // fetch the default search engine
        $bundleConfig = $container->getExtensionConfig('oro_file_inventor')[0];
        if (isset($bundleConfig['default_search_engine'])) {
            if (!$container->has($bundleConfig['default_search_engine'])
                || !($container->get($bundleConfig['default_search_engine'])) instanceof FileSearchEngineInterface
            ) {
                throw new \RunTimeException(sprintf(
                    'Default search engine "%s" is not defined',
                    $bundleConfig['default_search_engine']
                ));
            }
            $defaultSearchEngineId = $bundleConfig['default_search_engine'];
        } else {
            $defaultSearchEngineId = Configuration::DEFAULT_SEARCH_ENGINE;
        }

        // Add default search engine service to main inventor service
        $inventorService->addMethodCall(
            'addSearchEngine',
            [new Reference($defaultSearchEngineId), 'default']
        );
    }
}
