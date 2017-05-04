<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Domain;

class IsMatchTest extends DomainTest
{
    protected function getCookies()
    {
        return array(
            array(
                'domain' => '.example.com',
                'name' => 'foo',
                'value' => 'bar'
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
