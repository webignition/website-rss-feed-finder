<?php

class GetRssUrlTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__CLASS__, $this->getName() . '/HttpResponses')));
    }       

    public function testGetSingleRssUrl() {        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($this->getHttpClient());
        $finder->setRootUrl('http://codinghorror.com/blog/');
        
        $this->assertEquals(array('http://feeds.feedburner.com/codinghorror/'), $finder->getRssFeedUrls());        
    } 
    
    public function testGetMultipleRssUrls() {        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($this->getHttpClient());
        $finder->setRootUrl('http://korben.info/');

        
        $this->assertEquals(array(
            'http://korben.info/feed',
            'http://korben.info/wp-content/plugins/nextgen-gallery/xml/media-rss.php'
        ), $finder->getRssFeedUrls()); 
    }     
    
    
}