<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls;

class GetRootWebPageErrorTest extends GetFeedUrlTest {

    public function testHandleHttpClientError() {                
        $this->assertNull($this->getFeedFinder()->getRssFeedUrls());        
    } 
    
    public function testHandleHttpServerError() {                
        $this->assertNull($this->getFeedFinder()->getRssFeedUrls());        
    }     
    
    
}