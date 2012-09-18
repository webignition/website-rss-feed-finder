<?php

class GetRssUrlTest extends PHPUnit_Framework_TestCase {

    public function testGetCodingHorrorRssFeedUrl() {        
        $httpClient = new \webignition\Http\Mock\Client\Client();        
        $httpClient->getStoredResponseList()->setFixturesPath(__DIR__ . '/fixtures');
        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($httpClient);
        $finder->setRootUrl('http://codinghorror.com/blog/');
        
        $this->assertEquals('http://feeds.feedburner.com/codinghorror/', $finder->getRssFeedUrl());        
    }    
    
}