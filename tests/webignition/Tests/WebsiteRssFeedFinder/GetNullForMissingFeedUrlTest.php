<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetNullForMissingFeedUrlTest extends GetFeedUrlTest {    

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