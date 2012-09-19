<?php

class GetAtomUrlTest extends PHPUnit_Framework_TestCase {

    public function testGetGeekyPortalAtomUrl() {        
        $httpClient = new \webignition\Http\Mock\Client\Client();        
        $httpClient->getStoredResponseList()->setFixturesPath(__DIR__ . '/fixtures');
        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($httpClient);
        $finder->setRootUrl('http://www.geekyportal.com/');
        
        $this->assertEquals('http://www.geekyportal.com/feeds/posts/default', $finder->getAtomFeedUrl());        
    }    
    
}