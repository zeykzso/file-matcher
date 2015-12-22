<?php

namespace Oro\FileInventorBundle\Inventor\SymfonyFinder;

use Oro\FileInventorBundle\Inventor\FileLocation;
use Oro\FileInventorBundle\Inventor\FileSearchEngineInterface;
use Oro\FileInventorBundle\Inventor\FileSearchResult;
use Symfony\Component\Finder\Finder;

class SymfonyFinder implements FileSearchEngineInterface
{
    /**
     * @inheritdoc
     */
    public function searchString($stringToSearch, $searchFolder)
    {
        $finder = new Finder();
        $result = new FileSearchResult($searchFolder);
        $finder->ignoreUnreadableDirs()->in($searchFolder);
        foreach ($finder->files()->contains($stringToSearch) as $file) {
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
