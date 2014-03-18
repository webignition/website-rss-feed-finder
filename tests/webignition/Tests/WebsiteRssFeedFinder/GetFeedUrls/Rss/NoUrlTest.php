<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Rss;

class NoUrlTest extends GetUrlsTest { 

    protected function getExpectedFeedUrls() {
        return null;
    }      
    
}