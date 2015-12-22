<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testSearchPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Search')->form();
        $form['file_search[search]'] = 'config';

        $crawler = $client->submit($form);
        $fileRow = $crawler->filter('.file-info');

        // Match searched file names
        $fileNameTd = $fileRow->filter('.file-name');
        $this->assertEquals('SampleSymfonyConfiguration.yml', $fileNameTd->first()->html());
        $this->assertEquals('AnotherSampleSymfonyConfiguration.yml', $fileNameTd->last()->html());

        // Match searched file extensions
        $this->assertEquals('yml', $fileRow->first()->filter('kbd')->last()->html());
        $this->assertEquals('yml', $fileRow->eq(1)->filter('kbd')->last()->html());
    }

    public function testSearchPageIWithRegex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Search')->form();
        $form['file_search[search]'] = '/[1-3]{2}+/';
        $form['file_search[isRegex]'] = 1;

        $crawler = $client->submit($form);
        $fileRow = $crawler->filter('.file-info');
        $this->assertEquals('regex_test.txt', $fileRow->first()->filter('kbd')->first()->html());
        $this->assertEquals('SampleJsonResponse.json', $fileRow->eq(1)->filter('kbd')->first()->html());
        $this->assertEquals('AnotherSampleJsonResponse.json', $fileRow->eq(2)->filter('kbd')->first()->html());
    }
}
