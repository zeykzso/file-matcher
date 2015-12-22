<?php

namespace Oro\FileInventorBundle\Tests\Inventor;

use Oro\FileInventorBundle\DependencyInjection\Configuration;
use Oro\FileInventorBundle\Exception\MissingSearchEngineException;
use Oro\FileInventorBundle\Exception\MissingSearchFolderException;
use Oro\FileInventorBundle\Exception\RegexNotSupportedException;
use Oro\FileInventorBundle\Inventor\FileSearchEngineInterface;
use Oro\FileInventorBundle\Inventor\FileSearchResult;
use Oro\FileInventorBundle\Inventor\OroFileInventor;

class OroFileInventorTest extends \PHPUnit_Framework_TestCase
{
    protected $defaultFolders = [Configuration::DEFAULT_SEARCH_FOLDER];

    protected function getOroFileInventorInstance()
    {
        return new OroFileInventor($this->defaultFolders);
    }

    public function testNewInstanceThrowsExceptionIfInvalidFolderGiven()
    {
        $folders = [
            '/nonExistentFolder',
            Configuration::DEFAULT_SEARCH_FOLDER
        ];

        $this->setExpectedException(MissingSearchFolderException::class);

        new OroFileInventor($folders);
    }

    public function testNewInstanceDoesNotThrowExpcetionIfCorrectFolderGiven()
    {
        // instantiation should work without exception
        $this->getOroFileInventorInstance();
    }

    public function testMissingSearchEnginesShouldThrowException()
    {
        $inventor = $this->getOroFileInventorInstance();
        $this->setExpectedException(MissingSearchEngineException::class);
        $inventor->getSearchEngine('missing');
    }

    public function testDefaultSearchEngineThrowsExceptionIfMissing()
    {
        $inventor = $this->getOroFileInventorInstance();
        $this->setExpectedException(MissingSearchEngineException::class);
        $inventor->getDefaultSearchEngine();
    }

    public function testDefaultSearchEngineNotThrowsExceptionIfFound()
    {
        $inventor = $this->getOroFileInventorInstance();
        $inventor->addSearchEngine(\Phake::mock(FileSearchEngineInterface::class), 'default');
        $this->assertTrue($inventor->hasSearchEngine('default'));
        $this->assertInstanceOf(FileSearchEngineInterface::class, $inventor->getDefaultSearchEngine());
    }

    public function testSearchShouldThrowExceptionIfThereIsNoDirectoryToSearchIn()
    {
        $inventor = new OroFileInventor([]);
        $this->setExpectedException(MissingSearchFolderException::class);
        $inventor->search('test');
    }

    public function testSearchShouldThrowExceptionIfRegexNotSupportedAndRequested()
    {
        $inventor = $this->getOroFileInventorInstance();
        $defaultSearchEngine = \Phake::mock(FileSearchEngineInterface::class);
        $inventor->setDefaultSearchEngine($defaultSearchEngine);
        \Phake::when($defaultSearchEngine)->supportsRegex()->thenReturn(false);

        $isRegex = true;
        $this->setExpectedException(RegexNotSupportedException::class);
        $inventor->search('test', $isRegex);
    }

    public function testSearchShouldCallRegexSearchIfSpecified()
    {
        $inventor = $this->getOroFileInventorInstance();
        $defaultSearchEngine = \Phake::mock(FileSearchEngineInterface::class);
        $inventor->setDefaultSearchEngine($defaultSearchEngine);
        $searchFolder = $this->defaultFolders[0];
        \Phake::when($defaultSearchEngine)->searchRegex(\Phake::anyParameters())->thenReturn(new FileSearchResult($searchFolder));
        \Phake::when($defaultSearchEngine)->supportsRegex()->thenReturn(true);

        $isRegex = true;
        $inventor->search('test', $isRegex);
        \Phake::verify($defaultSearchEngine)->searchRegex('test', $searchFolder);
    }

    public function testSearcUsesDefaultSearchEngineIfNotProvided()
    {
        $inventor = $this->getOroFileInventorInstance();
        $defaultSearchEngine = \Phake::mock(FileSearchEngineInterface::class);
        $searchFolder = $this->defaultFolders[0];
        \Phake::when($defaultSearchEngine)->searchString(\Phake::anyParameters())->thenReturn(new FileSearchResult($searchFolder));
        $inventor->setDefaultSearchEngine($defaultSearchEngine);
        $string = 'test';
        $inventor->search($string, false, 'inexistent_search_engine');
        \Phake::verify($defaultSearchEngine)->searchString($string, $searchFolder);
    }
}
