<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Atom;

class MultipleUrlsTest extends GetUrlsTest
{
    protected function getExpectedFeedUrls()
    {
        return array(
            'http://example.com/atom-1.xml',
            'http://example.com/atom-2.xml'
        );
    }
}
