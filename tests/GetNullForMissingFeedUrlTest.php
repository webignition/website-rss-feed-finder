<?php

class GetNullForMissingFeedUrlTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__CLASS__, $this->getName() . '/HttpResponses')));
    }       

    public function testGetNullForMissingFeedUrl() {
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($this->getHttpClient());
        
        $finder->setRootUrl('http://codinghorror.com/blog/');        
        $this->assertNull($finder->getAtomFeedUrls());        
        
        $finder->setRootUrl('http://geekyportal.com/');        
        $this->assertNull($finder->getRssFeedUrls());           
    }    
    
}