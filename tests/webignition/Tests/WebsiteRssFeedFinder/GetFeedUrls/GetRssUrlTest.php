<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls;

class GetRssUrlTest extends GetFeedUrlTest {      

    public function testGetSingleRssUrl() {                
        $this->assertEquals(array('http://example.com/rss-1.xml'), $this->getFeedFinder()->getRssFeedUrls());        
    } 
    
    public function testGetMultipleRssUrls() {                
        $this->assertEquals(array(
            'http://example.com/rss-1.xml',
            'http://example.com/rss-2.xml'            
        ), $this->getFeedFinder()->getRssFeedUrls()); 
    }   
    
}