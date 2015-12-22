<?php

namespace Oro\FileInventorBundle\Inventor;

use Symfony\Component\DependencyInjection\ContainerInterface;

class OroFileInventor
{
    /**
     * @var FileSearchEngineInterface[]
     */
    protected $searchEngines = [];

    /**
     * @var array
     */
    protected $rootSearchFolders;

    /**
     * @param array $rootSearchFolders
     */
    public function __construct(array $rootSearchFolders)
    {
        $this->validateFolders($rootSearchFolders);
        $this->rootSearchFolders = $rootSearchFolders;
    }

    /**
     * @param FileSearchEngineInterface $searchEngine
     * @param string $alias
     */
    public function addSearchEngine(FileSearchEngineInterface $searchEngine, $alias)
    {
        $this->searchEngines[$alias] = $searchEngine;
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasSearchEngine($alias)
    {
        return isset($this->searchEngines[$alias]);
    }

    /**
     * @param string $alias
     * @return FileSearchEngineInterface
     */
    public function getSearchEngine($alias)
    {
        return $this->searchEngines[$alias];
    }

    /**
     * @return FileSearchEngineInterface
     */
    public function getDefaultSearchEngine()
    {
        return $this->searchEngines['default'];
    }

    /**
     * @param string $searchString
     *
     * @param bool $isRegex
     * @param string|null $searchEngineAlias
     * @return FolderGroupSearchResult
     */
    public function search($searchString, $isRegex = false, $searchEngineAlias = null): FolderGroupSearchResult
    {
        if (empty($this->rootSearchFolders)) {
            throw new \InvalidArgumentException(sprintf('There are no search folders defined to search "%s" in', $searchString));
        }

        $searchEngine = (!is_null($searchEngineAlias) && $this->hasSearchEngine($searchEngineAlias))
            ? $this->getSearchEngine($searchEngineAlias)
            : $this->getDefaultSearchEngine();

        if ($isRegex && !$searchEngine->supportsRegex()) {
            throw new \InvalidArgumentException(sprintf('Search engine "%s" does not support regex search', get_class($searchEngine)));
        }

        $groupResult = new FolderGroupSearchResult();
        foreach ($this->rootSearchFolders as $searchFolder) {
            $groupResult->add(
                $isRegex
                    ? $searchEngine->searchRegex($searchString, $searchFolder)
                    : $searchEngine->searchString($searchString, $searchFolder)
            );
        }

        return $groupResult;
    }

    /**
     * @param array $folders
     *
     * @throws \InvalidArgumentException
     */
    protected function validateFolders(array $folders)
    {
        foreach ($folders as $folder) {
            if (!is_dir($folder)) {
                throw new \InvalidArgumentException(sprintf('Invalid directory provided for the file search: %s', $folder));
            }
        }
    }
}
