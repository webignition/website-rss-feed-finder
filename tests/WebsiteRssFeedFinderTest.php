<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use QueryPath\Exception as QueryPathException;
use webignition\Tests\WebsiteRssFeedFinder\Factory\HtmlDocumentFactory;
use webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder;
use GuzzleHttp\Client as HttpClient;

class WebsiteRssFeedFinderTest extends \PHPUnit\Framework\TestCase
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
     * @var MockHandler
     */
    private $mockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $this->httpClient = new HttpClient([
            'handler' => $handlerStack,
        ]);

        $this->websiteRssFeedFinder = new WebsiteRssFeedFinder($this->httpClient);
        $this->websiteRssFeedFinder->setRootUrl('http://example.com/');
    }

    /**
     * @dataProvider getRssFeedUrlsDataProvider
     *
     * @param array $httpFixtures
     * @param string[] $expectedRssUrls
     *
     * @throws GuzzleException
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
                    new Response(404),
                ],
                'expectedRssUrls' => [],
            ],
            'root web page not web page' => [
                'httpFixtures' => [
                    new Response(200, ['content-type' => 'application/xml']),
                ],
                'expectedRssUrls' => [],
            ],
            'no urls' => [
                'httpFixtures' => [
                    new Response(200, ['content-type' => 'text/html']),
                    new Response(200, ['content-type' => 'text/html'], HtmlDocumentFactory::load('empty')),
                ],
                'expectedRssUrls' => [],
            ],
            'single url' => [
                'httpFixtures' => [
                    new Response(200, ['content-type' => 'text/html']),
                    new Response(200, ['content-type' => 'text/html'], HtmlDocumentFactory::load('single-rss')),
                ],
                'expectedRssUrls' => [
                    'http://example.com/rss-1.xml',
                ],
            ],
            'two urls' => [
                'httpFixtures' => [
                    new Response(200, ['content-type' => 'text/html']),
                    new Response(200, ['content-type' => 'text/html'], HtmlDocumentFactory::load('two-rss')),
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
     * @throws GuzzleException
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
                    new Response(404),
                ],
                'expectedRssUrls' => [],
            ],
            'root web page not web page' => [
                'httpFixtures' => [
                    new Response(200, ['content-type' => 'application/xml']),
                ],
                'expectedRssUrls' => [],
            ],
            'no urls' => [
                'httpFixtures' => [
                    new Response(200, ['content-type' => 'text/html']),
                    new Response(200, ['content-type' => 'text/html'], HtmlDocumentFactory::load('empty')),
                ],
                'expectedRssUrls' => [],
            ],
            'single url' => [
                'httpFixtures' => [
                    new Response(200, ['content-type' => 'text/html']),
                    new Response(200, ['content-type' => 'text/html'], HtmlDocumentFactory::load('single-atom')),
                ],
                'expectedRssUrls' => [
                    'http://example.com/atom-1.xml',
                ],
            ],
            'two urls' => [
                'httpFixtures' => [
                    new Response(200, ['content-type' => 'text/html']),
                    new Response(200, ['content-type' => 'text/html'], HtmlDocumentFactory::load('two-atom')),
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
        foreach ($fixtures as $fixture) {
            $this->mockHandler->append($fixture);
        }
    }
}
