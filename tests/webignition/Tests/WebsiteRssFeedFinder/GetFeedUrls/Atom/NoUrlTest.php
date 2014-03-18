<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Atom;

class NoUrlTest extends GetUrlsTest { 

    protected function getExpectedFeedUrls() {
        return null;
    }      
    
}