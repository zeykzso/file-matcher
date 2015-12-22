<?php

namespace Oro\FileInventorBundle\Inventor;

interface FileSearchEngineInterface
{
    /**
     * Locate a string and return the list of files which contains it
     *
     * @param string $stringToSearch
     * @param string $searchFolder
     * @return FileSearchResult
     */
    public function searchString($stringToSearch, $searchFolder);

    /**
     * @return bool
     */
    public function supportsRegex();

    /**
     * @param string $regex
     * @param string $searchFolder
     * @return FileSearchResult
     */
    public function searchRegex($regex, $searchFolder);
}
