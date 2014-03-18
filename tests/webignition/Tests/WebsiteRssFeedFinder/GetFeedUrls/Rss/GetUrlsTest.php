<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Rss;

use webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\GetFeedUrlsTest;

abstract class GetUrlsTest extends GetFeedUrlsTest { 
    
    protected function getFeedUrls() {
        return $this->getFeedFinder()->getRssFeedUrls();
    }        
    
}