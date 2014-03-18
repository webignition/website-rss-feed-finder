<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Secure;

class IsMatchTest extends SecureTest {   
    
    protected function getRootUrl() {
        return 'https://example.com/';
    }

    protected function getExpectedRequestsOnWhichCookiesShouldBeSet() {
        return $this->getAllSentHttpRequests();
    }

    protected function getExpectedRequestsOnWhichCookiesShouldNotBeSet() {
        return array();
    }    
}