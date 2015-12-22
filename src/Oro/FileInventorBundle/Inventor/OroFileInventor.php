<?php

namespace Oro\FileInventorBundle\Inventor;

use Symfony\Component\DependencyInjection\ContainerInterface;

class OroFileInventor
{
    /**
     * @var FileSearchEngineInterface
     */
    protected $searchEngine;

    /**
     * @var array
     */
    protected $rootSearchFolders;

    /**
     * @param ContainerInterface $container
     * @param array $rootSearchFolders
     * @param string $searchEngineName
     */
    public function __construct(ContainerInterface $container, array $rootSearchFolders, string $searchEngineName)
    {
        $this->rootSearchFolders = $rootSearchFolders;
        $this->searchEngine = $container->get($searchEngineName);
    }

    /**
     * @param string $searchString
     *
     * @return FolderGroupSearchResult
     */
    public function searchString($searchString): FolderGroupSearchResult
    {
        $groupResult = new FolderGroupSearchResult();
        foreach ($this->rootSearchFolders as $searchFolder) {
            $groupResult->add(
                $this->searchEngine->searchString($searchString, $searchFolder)
            );
        }

        return $groupResult;
    }
}
