<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\HttpErrorRetrievingRootWebPage;

use webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\GetFeedUrlsTest;

class ClientErrorTest extends GetFeedUrlsTest {
    
    protected function getExpectedFeedUrls() {
        return null;
    }

    protected function getFeedUrls() {
        return $this->getFeedFinder()->getRssFeedUrls();
    }
    
}