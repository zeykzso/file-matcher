<?php

namespace Oro\FileInventorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    const DEFAULT_SEARCH_FOLDER = __DIR__ . '/../Tests/ExampleFileRepository';
    const DEFAULT_SEARCH_ENGINE = 'oro_file_inventor.symfony_finder';

    const INVENTOR_SERVICE = 'oro_file_inventor';
    const SEARCH_ENGINE_TAG = 'file_inventor_search_engine';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('oro_file_inventor');

        $rootNode
            ->children()
                ->arrayNode('root_search_folders')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->scalarNode('default_search_engine')
                ->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
