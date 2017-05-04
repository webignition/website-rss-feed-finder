<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls;

use webignition\Tests\WebsiteRssFeedFinder\BaseTest;

abstract class GetFeedUrlsTest extends BaseTest
{
    const ROOT_WEB_PAGE_URL = 'http://www.example.com/';

    /**
     * @return string[]
     */
    abstract protected function getExpectedFeedUrls();

    /**
     * @return string[]
     */
    abstract protected function getFeedUrls();

    public function setUp()
    {
        $this->setHttpFixtures(
            $this->getHttpFixtures(
                $this->getFixturesDataPath(get_class($this), $this->getName() . '/HttpResponses')
            )
        );

        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl(self::ROOT_WEB_PAGE_URL);
    }

    public function testGetUrls()
    {
        $this->assertEquals($this->getExpectedFeedUrls(), $this->getFeedUrls());
    }
}
