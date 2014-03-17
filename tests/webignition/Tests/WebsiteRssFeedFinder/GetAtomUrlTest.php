<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetAtomUrlTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__CLASS__, $this->getName() . '/HttpResponses')));
    }    

    public function testGetSingleAtomUrl() {
        $finder = $this->getFeedFinder();
        $finder->setRootUrl('http://www.example.com/');
        
        $this->assertEquals(array('http://www.geekyportal.com/feeds/posts/default'), $finder->getAtomFeedUrls());        
    }    
    
    
    public function testGetMultipleAtomUrls() {
        $finder = $this->getFeedFinder();
        $finder->setRootUrl('http://www.geekyportal.com/');
        
        $this->assertEquals(array(
            'http://www.geekyportal.com/feeds/posts/default',
            'http://www.geekyportal.com/feeds/posts/alternative'            
        ), $finder->getAtomFeedUrls());        
    }        
    
}