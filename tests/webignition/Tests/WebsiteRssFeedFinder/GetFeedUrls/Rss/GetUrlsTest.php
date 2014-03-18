<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Rss;

use webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\GetFeedUrlsTest;

class GetUrlsTest extends GetFeedUrlsTest {      

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