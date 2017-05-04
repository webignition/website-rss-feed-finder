<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Domain;

class NotMatchTest extends DomainTest
{
    protected function getCookies()
    {
        return array(
            array(
                'domain' => '.foo.example.com',
                'name' => 'foo',
                'value' => 'bar'
            )
        );
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
