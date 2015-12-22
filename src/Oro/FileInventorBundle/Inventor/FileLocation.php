<?php

namespace Oro\FileInventorBundle\Inventor;

class FileLocation
{
    /**
     * @var string
     */
    protected $folder;

    /**
     * @var string[]
     */
    protected $containingLines;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param mixed $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return mixed
     */
    public function getContainingLines()
    {
        return $this->containingLines;
    }

    /**
     * @param mixed $containingLines
     */
    public function setContainingLines($containingLines)
    {
        $this->containingLines = $containingLines;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
