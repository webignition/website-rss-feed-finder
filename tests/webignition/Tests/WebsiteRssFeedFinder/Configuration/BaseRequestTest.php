<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Configuration;

use Guzzle\Http\Message\Request;

class BaseRequestTest extends ConfigurationTest
{
    public function testGetDefaultBaseRequest()
    {
        $this->assertInstanceOf(Request::class, $this->configuration->getBaseRequest());
    }

    public function testSetReturnsSelf()
    {
        $this->assertEquals($this->configuration, $this->configuration->setBaseRequest($this->getHttpClient()->get()));
    }

    public function testSetGetBaseRequest()
    {
        $baseRequest = $this->getHttpClient()->get();
        $baseRequest->setAuth('example_user', 'example_password');

        $this->configuration->setBaseRequest($baseRequest);

        $this->assertEquals('example_user', $this->configuration->getBaseRequest()->getUsername());
        $this->assertEquals($baseRequest->getUsername(), $this->configuration->getBaseRequest()->getUsername());
    }
}
