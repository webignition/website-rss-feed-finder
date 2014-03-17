<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetNullForMissingFeedUrlTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__CLASS__, $this->getName() . '/HttpResponses')));
    }       

    public function testGetNullForMissingFeedUrl() {
        $finder = $this->getFeedFinder();        
        
        $finder->getConfiguration()->setRootUrl('http://codinghorror.com/blog/');                
        $finder->reset();
        $this->assertNull($finder->getAtomFeedUrls());        
        
        $finder->getConfiguration()->setRootUrl('http://geekyportal.com/');        
        $finder->reset();
        $this->assertNull($finder->getRssFeedUrls());           
    }    
    
}