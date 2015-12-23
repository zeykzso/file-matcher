<?php

namespace Oro\FileInventorBundle\Inventor;

use Oro\FileInventorBundle\Exception\MissingSearchEngineException;
use Oro\FileInventorBundle\Exception\MissingSearchFolderException;
use Oro\FileInventorBundle\Exception\RegexNotSupportedException;
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
     *
     * @return bool
     */
    public function hasSearchEngine($alias)
    {
        return array_key_exists($alias, $this->searchEngines);
    }

    /**
     * @param string $alias
     *
     * @throws MissingSearchEngineException
     * @return FileSearchEngineInterface
     */
    public function getSearchEngine($alias)
    {
        if (!array_key_exists($alias, $this->searchEngines)) {
            throw new MissingSearchEngineException(sprintf('Search engine with alias "%s" is missing', $alias));
        }

        return $this->searchEngines[$alias];
    }

    /**
     * @param FileSearchEngineInterface $searchEngine
     */
    public function setDefaultSearchEngine(FileSearchEngineInterface $searchEngine)
    {
        $this->searchEngines['default'] = $searchEngine;
    }

    /**
     * @return FileSearchEngineInterface
     */
    public function getDefaultSearchEngine()
    {
        return $this->getSearchEngine('default');
    }

    /**
     * @param string $searchString
     * @param bool $isRegex
     * @param string|null $searchEngineAlias
     *
     * @throws MissingSearchEngineException
     * @throws MissingSearchFolderException
     * @throws RegexNotSupportedException
     * @return FolderGroupSearchResult
     */
    public function search($searchString, $isRegex = false, $searchEngineAlias = null)
    {
        if (empty($this->rootSearchFolders)) {
            throw new MissingSearchFolderException(sprintf('There are no search folders defined to search "%s" in', $searchString));
        }

        $searchEngine = (!is_null($searchEngineAlias) && $this->hasSearchEngine($searchEngineAlias))
            ? $this->getSearchEngine($searchEngineAlias)
            : $this->getDefaultSearchEngine();

        if ($isRegex && !$searchEngine->supportsRegex()) {
            throw new RegexNotSupportedException(sprintf('Search engine "%s" does not support regex search', get_class($searchEngine)));
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
     * @throws MissingSearchFolderException
     * @return bool
     */
    protected function validateFolders(array $folders)
    {
        foreach ($folders as $folder) {
            if (!is_dir($folder)) {
                throw new MissingSearchFolderException(sprintf('Invalid directory provided for the file search: %s', $folder));
            }
        }

        return true;
    }
}
