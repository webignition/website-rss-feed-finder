<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Path;

use webignition\Tests\WebsiteRssFeedFinder\Cookies\CookiesTest;

abstract class PathTest extends CookiesTest
{
    protected function getRootUrl()
    {
        return 'http://example.com/foo';
    }
}
