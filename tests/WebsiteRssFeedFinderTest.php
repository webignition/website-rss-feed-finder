<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\WebsiteRssFeedFinder\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use webignition\WebsiteRssFeedFinder\Tests\Factory\HtmlDocumentFactory;
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
     */
    public function testGetRssFeedUrls(array $httpFixtures, array $expectedRssUrls)
    {
        $this->setHttpFixtures($httpFixtures);
        $this->assertEquals($expectedRssUrls, $this->websiteRssFeedFinder->getRssFeedUrls());
    }

    public function getRssFeedUrlsDataProvider(): array
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
     */
    public function testGetAtomFeedUrls(array $httpFixtures, array $expectedRssUrls)
    {
        $this->setHttpFixtures($httpFixtures);
        $this->assertEquals($expectedRssUrls, $this->websiteRssFeedFinder->getAtomFeedUrls());
    }

    public function getAtomFeedUrlsDataProvider(): array
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

    private function setHttpFixtures(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->mockHandler->append($fixture);
        }
    }
}
