<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Configuration;

use webignition\WebsiteRssFeedFinder\Configuration;
use webignition\Tests\WebsiteRssFeedFinder\BaseTest;

abstract class ConfigurationTest extends BaseTest
{
    /**
     *
     * @var Configuration
     */
    protected $configuration;

    public function setUp()
    {
        parent::setUp();
        $this->configuration = new Configuration();
    }
}
