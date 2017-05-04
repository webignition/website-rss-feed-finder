<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Configuration;

class RootUrlTest extends ConfigurationTest
{
    const ROOT_URL = 'http://example.com/';

    public function testDefaultIsNull()
    {
        $this->assertNull($this->configuration->getRootUrl());
    }

    public function testSetReturnsSelf()
    {
        $this->assertEquals($this->configuration, $this->configuration->setRootUrl(self::ROOT_URL));
    }

    public function testGetReturnsValueSet()
    {
        $this->assertEquals(
            self::ROOT_URL,
            (string)$this->configuration->setRootUrl(self::ROOT_URL)->getRootUrl()
        );
    }

    public function testGetReturnsNormalizedUrlObject()
    {
        $this->assertInstanceOf(
            'webignition\NormalisedUrl\NormalisedUrl',
            $this->configuration->setRootUrl(self::ROOT_URL)->getRootUrl()
        );
    }
}
