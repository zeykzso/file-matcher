<?php

namespace Oro\FileInventorBundle\Inventor;

class FolderGroupSearchResult implements \Iterator
{
    /**
     * @var FileSearchResult[]
     */
    protected $groups = [];

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @inheritdoc
     *
     * @return FileSearchResult
     */
    public function current()
    {
        return $this->groups[$this->index];
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
    public function valid()
    {
        return array_key_exists($this->index, $this->groups);
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
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @param FileSearchResult $fileSearchResult
     */
    public function add(FileSearchResult $fileSearchResult)
    {
        $this->groups[] = $fileSearchResult;
    }
}
