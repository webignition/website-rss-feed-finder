<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Configuration;

class CookiesTest extends ConfigurationTest
{
    public function testGetDefaultCookieCollection()
    {
        $this->assertEquals(array(), $this->configuration->getCookies());
    }

    public function testSetReturnsSelf()
    {
        $this->assertEquals($this->configuration, $this->configuration->setCookies(array()));
    }

    public function testSetGetCookies()
    {
        $cookies = array(
            array(
                'name' => 'name1',
                'value' => 'value1'
            ),
            array(
                'domain' => '.example.com',
                'name' => 'name2',
                'value' => 'value2'
            ),
            array(
                'domain' => '.example.com',
                'secure' => true,
                'name' => 'name3',
                'value' => 'value3'
            )
        );

        $this->assertEquals($cookies, $this->configuration->setCookies($cookies)->getCookies());
    }
}
