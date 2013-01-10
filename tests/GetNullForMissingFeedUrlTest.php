<?php

class GetnUllForMissingFeedUrlTest extends PHPUnit_Framework_TestCase {

    public function testGetNullForMissingFeedUrl() {        
        $httpClient = new \webignition\Http\Mock\Client\Client();        
        $httpClient->getStoredResponseList()->setFixturesPath(__DIR__ . '/fixtures');
        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($httpClient);
        
        $finder->setRootUrl('http://codinghorror.com/blog/');        
        $this->assertNull($finder->getAtomFeedUrls());        
        
        $finder->setRootUrl('http://geekyportal.com/');        
        $this->assertNull($finder->getRssFeedUrls());           
    }    
    
}