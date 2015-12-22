<?php

namespace Oro\FileInventorBundle\Inventor;

class FileSearchResult implements \Iterator
{
    /**
     * @var FileLocation[]
     */
    protected $fileLocationList = [];

    /**
     * @var int
     */
    protected $index;

    /**
     * @var string
     */
    protected $rootSearchFolder;

    /**
     * @param string $rootSearchFolder
     */
    public function __construct($rootSearchFolder)
    {
        $this->rootSearchFolder = $rootSearchFolder;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return $this->fileLocationList[$this->index];
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return array_key_exists($this->index, $this->fileLocationList);
    }

    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Add new search result to the list
     *
     * @param FileLocation $fileLocation
     */
    public function add(FileLocation $fileLocation)
    {
        $this->fileLocationList[] = $fileLocation;
    }

    /**
     * Clear file search result list
     */
    public function clear()
    {
        $this->fileLocationList = [];
        $this->rewind();
    }

    /**
     * @return string
     */
    public function getRootSearchFolder()
    {
        return $this->rootSearchFolder;
    }
}
