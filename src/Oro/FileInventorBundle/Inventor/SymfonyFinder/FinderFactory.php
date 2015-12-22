<?php

namespace Oro\FileInventorBundle\Inventor\SymfonyFinder;

use Symfony\Component\Finder\Finder;

class FinderFactory
{
    /**
     * @return Finder
     */
    public function create()
    {
        return new Finder();
    }
}
