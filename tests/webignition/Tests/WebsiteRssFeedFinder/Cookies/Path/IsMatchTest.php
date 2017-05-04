<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Path;

class IsMatchTest extends PathTest
{
    protected function getCookies()
    {
        return array(
            array(
                'domain' => '.example.com',
                'name' => 'foo',
                'value' => 'bar',
                'path' => '/foo'
            )
        );
    }

    protected function getExpectedRequestsOnWhichCookiesShouldBeSet()
    {
        return $this->getAllSentHttpRequests();
    }

    protected function getExpectedRequestsOnWhichCookiesShouldNotBeSet()
    {
        return array();
    }
}
