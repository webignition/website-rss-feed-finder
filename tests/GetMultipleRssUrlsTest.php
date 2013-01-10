<?php

class GetMultipleRssUrlsTest extends PHPUnit_Framework_TestCase {

    public function testGetMultipleRssUrls() {                
        $httpClient = new \webignition\Http\Mock\Client\Client();        
        $httpClient->getStoredResponseList()->setFixturesPath(__DIR__ . '/fixtures');
        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($httpClient);
        $finder->setRootUrl('http://korben.info/');
        
        $this->assertEquals(array(
            'http://korben.info/feed',
            'http://korben.info/wp-content/plugins/nextgen-gallery/xml/media-rss.php'
        ), $finder->getRssFeedUrls());        
    }    
    
}