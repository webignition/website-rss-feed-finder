<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetRootWebPageErrorTest extends GetFeedUrlTest {

    public function testHandleHttpClientError() {        
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl('http://example.com/');
        
        $this->assertNull($finder->getRssFeedUrls());        
    } 
    
    public function testHandleHttpServerError() {        
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl('http://example.com/');
        
        $this->assertNull($finder->getRssFeedUrls());        
    }     
    
    
}