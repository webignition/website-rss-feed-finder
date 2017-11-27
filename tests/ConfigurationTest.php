<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

use webignition\WebsiteRssFeedFinder\Configuration;
use GuzzleHttp\Client as HttpClient;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new Configuration();
        $this->httpClient = new HttpClient();
    }

    public function testGetHttpClientWhenNotSet()
    {
        $httpClient = $this->configuration->getHttpClient();

        $this->assertNotEquals(
            spl_object_hash($this->httpClient),
            spl_object_hash($httpClient)
        );
    }

    public function testGetHttpClientWhenSet()
    {
        $this->configuration->setHttpClient($this->httpClient);
        $httpClient = $this->configuration->getHttpClient();

        $this->assertEquals(
            spl_object_hash($this->httpClient),
            spl_object_hash($httpClient)
        );
    }

    /**
     * @dataProvider getRootUrlDataProvider
     *
     * @param $rootUrl
     * @param $expectedRootUrl
     */
    public function testGetRootUrl($rootUrl, $expectedRootUrl)
    {
        if (!is_null($rootUrl)) {
            $this->configuration->setRootUrl($rootUrl);
        }

        $this->assertEquals(
            $expectedRootUrl,
            (string) $this->configuration->getRootUrl()
        );
    }

    /**
     * @return array
     */
    public function getRootUrlDataProvider()
    {
        return [
            'null' => [
                'rootUrl' => null,
                'expectedRootUrl' => null,
            ],
            'empty' => [
                'rootUrl' => '',
                'expectedRootUrl' => null,
            ],
            'non-empty' => [
                'rootUrl' => 'http://example.com/',
                'expectedRootUrl' => 'http://example.com/',
            ],
            'normalised' => [
                'rootUrl' => 'http://example.com/?b=2&a=1',
                'expectedRootUrl' => 'http://example.com/?a=1&b=2',
            ],
        ];
    }
}
