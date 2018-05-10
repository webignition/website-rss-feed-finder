<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

use QueryPath\Exception as QueryPathException;
use webignition\Tests\WebsiteRssFeedFinder\Factory\HtmlDocumentFactory;
use webignition\Tests\WebsiteRssFeedFinder\Factory\HttpFixtureFactory;
use webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder;
use GuzzleHttp\Subscriber\Mock as HttpMockSubscriber;
use GuzzleHttp\Client as HttpClient;

class WebsiteRssFeedFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var WebsiteRssFeedFinder
     */
    private $websiteRssFeedFinder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpClient = new HttpClient();
        $this->websiteRssFeedFinder = new WebsiteRssFeedFinder($this->httpClient);
        $this->websiteRssFeedFinder->setRootUrl('http://example.com/');
    }

    /**
     * @dataProvider getRssFeedUrlsDataProvider
     *
     * @param array $httpFixtures
     * @param string[] $expectedRssUrls
     *
     * @throws QueryPathException
     */
    public function testGetRssFeedUrls($httpFixtures, $expectedRssUrls)
    {
        $this->setHttpFixtures($httpFixtures);
        $this->assertEquals($expectedRssUrls, $this->websiteRssFeedFinder->getRssFeedUrls());
    }

    /**
     * @return array
     */
    public function getRssFeedUrlsDataProvider()
    {
        return [
            '404 retrieving root web page' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createNotFoundResponse(),
                ],
                'expectedRssUrls' => [],
            ],
            'root web page not web page' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createSuccessResponse('application/xml'),
                ],
                'expectedRssUrls' => [],
            ],
            'no urls' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createSuccessResponse('text/html', HtmlDocumentFactory::load('empty')),
                ],
                'expectedRssUrls' => [],
            ],
            'single url' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createSuccessResponse('text/html', HtmlDocumentFactory::load('single-rss')),
                ],
                'expectedRssUrls' => [
                    'http://example.com/rss-1.xml',
                ],
            ],
            'two urls' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createSuccessResponse('text/html', HtmlDocumentFactory::load('two-rss')),
                ],
                'expectedRssUrls' => [
                    'http://example.com/rss-1.xml',
                    'http://example.com/rss-2.xml',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getAtomFeedUrlsDataProvider
     *
     * @param array $httpFixtures
     * @param string[] $expectedRssUrls
     *
     * @throws QueryPathException
     */
    public function testGetAtomFeedUrls($httpFixtures, $expectedRssUrls)
    {
        $this->setHttpFixtures($httpFixtures);
        $this->assertEquals($expectedRssUrls, $this->websiteRssFeedFinder->getAtomFeedUrls());
    }

    /**
     * @return array
     */
    public function getAtomFeedUrlsDataProvider()
    {
        return [
            '404 retrieving root web page' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createNotFoundResponse(),
                ],
                'expectedRssUrls' => [],
            ],
            'root web page not web page' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createSuccessResponse('application/xml'),
                ],
                'expectedRssUrls' => [],
            ],
            'no urls' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createSuccessResponse('text/html', HtmlDocumentFactory::load('empty')),
                ],
                'expectedRssUrls' => [],
            ],
            'single url' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createSuccessResponse('text/html', HtmlDocumentFactory::load('single-atom')),
                ],
                'expectedRssUrls' => [
                    'http://example.com/atom-1.xml',
                ],
            ],
            'two urls' => [
                'httpFixtures' => [
                    HttpFixtureFactory::createSuccessResponse('text/html', HtmlDocumentFactory::load('two-atom')),
                ],
                'expectedRssUrls' => [
                    'http://example.com/atom-1.xml',
                    'http://example.com/atom-2.xml',
                ],
            ],
        ];
    }

    /**
     * @param array $fixtures
     */
    private function setHttpFixtures($fixtures)
    {
        $httpMockSubscriber = new HttpMockSubscriber($fixtures);

        $this->httpClient->getEmitter()->attach($httpMockSubscriber);
    }
}
