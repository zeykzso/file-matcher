<?php

namespace Oro\FileInventorBundle\Tests\Inventor\SymfonyFinder;

use Oro\FileInventorBundle\Inventor\FileLocation;
use Oro\FileInventorBundle\Inventor\FileSearchResult;
use Oro\FileInventorBundle\Inventor\SymfonyFinder\FinderFactory;
use Oro\FileInventorBundle\Inventor\SymfonyFinder\SymfonyFinder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class SymfonyFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Finder
     */
    protected $finderComponent;

    /**
     * @var SymfonyFinder
     */
    protected $symfonyFinder;

    /**
     * @var FinderFactory
     */
    protected $finderFactory;

    public function setUp()
    {
        $this->finderComponent = \Phake::mock(Finder::class);
        $this->finderFactory = \Phake::mock(FinderFactory::class);
        \Phake::when($this->finderFactory)->create()->thenReturn($this->finderComponent);
        $this->symfonyFinder = new SymfonyFinder($this->finderFactory);
    }

    public function testSearchStringShouldReturnFileSearchResultInstance()
    {
        \Phake::when($this->finderComponent)->files()->thenReturn($this->finderComponent);
        \Phake::when($this->finderComponent)->ignoreUnreadableDirs()->thenReturn($this->finderComponent);
        $splFile = \Phake::mock(SplFileInfo::class);
        \Phake::when($this->finderComponent)->contains(\Phake::anyParameters())->thenReturn([$splFile]);
        $result = $this->symfonyFinder->searchString('test', 'testFolder');
        $this->assertInstanceOf(FileSearchResult::class, $result);

        foreach ($result as $fileLocation) {
            $this->assertInstanceOf(FileLocation::class, $fileLocation);
        }
    }
}
