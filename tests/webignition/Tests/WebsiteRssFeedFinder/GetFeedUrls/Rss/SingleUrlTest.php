<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Rss;

class SingleUrlTest extends GetUrlsTest {      
    
    protected function getExpectedFeedUrls() {
        return array(
            'http://example.com/rss-1.xml',
        );
    } 
    
}