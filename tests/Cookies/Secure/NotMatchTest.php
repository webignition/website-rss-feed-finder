<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Secure;

class NotMatchTest extends SecureTest
{
    protected function getRootUrl()
    {
        return 'http://example.com/';
    }

    protected function getExpectedRequestsOnWhichCookiesShouldBeSet()
    {
        return array();
    }

    protected function getExpectedRequestsOnWhichCookiesShouldNotBeSet()
    {
        return $this->getAllSentHttpRequests();
    }
}
