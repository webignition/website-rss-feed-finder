<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Rss;

class MultipleUrlsTest extends GetUrlsTest {      
    
    protected function getExpectedFeedUrls() {
        return array(
            'http://example.com/rss-1.xml',
            'http://example.com/rss-2.xml' 
        );
    }  
    
}