<?php

namespace Oro\FileInventorBundle\Inventor\SymfonyFinder;

use Oro\FileInventorBundle\Inventor\FileLocation;
use Oro\FileInventorBundle\Inventor\FileSearchEngineInterface;
use Oro\FileInventorBundle\Inventor\FileSearchResult;
use Symfony\Component\Finder\Finder;

class SymfonyFinder implements FileSearchEngineInterface
{
    /**
     * @var FinderFactory
     */
    protected $finderFactory;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @param FinderFactory $finderFactory
     */
    public function __construct(FinderFactory $finderFactory)
    {
        $this->finderFactory = $finderFactory;
    }

    /**
     * @inheritdoc
     */
    public function searchString($stringToSearch, $searchFolder)
    {
        $this->finder = $this->finderFactory->create();
        $result = new FileSearchResult($searchFolder);
        $this->finder->ignoreUnreadableDirs()->in($searchFolder);
        foreach ($this->finder->files()->contains($stringToSearch) as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            $fileLocation = new FileLocation();
            $fileLocation->setFolder($file->getPath());
            $fileLocation->setName($file->getFilename());
            $fileLocation->setExtension($file->getExtension());
            $result->add($fileLocation);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function supportsRegex()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function searchRegex($regex, $searchFolder)
    {
        return $this->searchString($regex, $searchFolder);
    }
}
