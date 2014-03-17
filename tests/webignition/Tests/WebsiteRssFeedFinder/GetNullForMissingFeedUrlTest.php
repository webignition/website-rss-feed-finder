<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetNullForMissingFeedUrlTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__CLASS__, $this->getName() . '/HttpResponses')));
    }       

    public function testGetNullForMissingFeedUrl() {
        $finder = $this->getFeedFinder();
        
        $finder->setRootUrl('http://codinghorror.com/blog/');        
        $this->assertNull($finder->getAtomFeedUrls());        
        
        $finder->setRootUrl('http://geekyportal.com/');        
        $this->assertNull($finder->getRssFeedUrls());           
    }    
    
}