<?php

class GetAtomUrlTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__CLASS__, $this->getName() . '/HttpResponses')));
    }    

    public function testGetSingleAtomUrl() {
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($this->getHttpClient());
        $finder->setRootUrl('http://www.example.com/');
        
        $this->assertEquals(array('http://www.geekyportal.com/feeds/posts/default'), $finder->getAtomFeedUrls());        
    }    
    
    
    public function testGetMultipleAtomUrls() {
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($this->getHttpClient());
        $finder->setRootUrl('http://www.geekyportal.com/');
        
        $this->assertEquals(array(
            'http://www.geekyportal.com/feeds/posts/default',
            'http://www.geekyportal.com/feeds/posts/alternative'            
        ), $finder->getAtomFeedUrls());        
    }        
    
}