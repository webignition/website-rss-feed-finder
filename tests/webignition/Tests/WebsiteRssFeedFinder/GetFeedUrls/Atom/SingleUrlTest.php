<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Atom;

class SingleUrlTest extends GetUrlsTest
{
    protected function getExpectedFeedUrls()
    {
        return array(
            'http://example.com/atom-1.xml'
        );
    }
}
