<?php

namespace webignition\WebsiteRssFeedFinder;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use QueryPath\ParseException;
use webignition\NormalisedUrl\NormalisedUrl;
use webignition\WebResource\Retriever as WebResourceRetriever;
use webignition\WebResource\WebPage\WebPage;
use webignition\WebResourceInterfaces\WebPageInterface;

class WebsiteRssFeedFinder
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var NormalisedUrl
     */
    private $rootUrl = null;

    /**
     * @var array
     */
    private $feedUrls = [];

    /**
     * @var string[]
     */
    private $supportedFeedTypes = [
        'application/rss+xml',
        'application/atom+xml'
    ];

    /**
     * @var WebResourceRetriever
     */
    private $webResourceRetriever;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;

        $this->webResourceRetriever = new WebResourceRetriever(
            $this->httpClient,
            WebPage::getModelledContentTypeStrings(),
            false
        );
    }

    /**
     * @param string $url
     */
    public function setRootUrl($url)
    {
        $this->rootUrl = new NormalisedUrl($url);
        $this->feedUrls = [];
    }

    /**
     * @return string[]
     *
     * @throws GuzzleException
     */
    public function getRssFeedUrls()
    {
        return $this->getLinkHref('application/rss+xml');
    }

    /**
     * @return string[]
     *
     * @throws GuzzleException
     */
    public function getAtomFeedUrls()
    {
        return $this->getLinkHref('application/atom+xml');
    }

    /**
     * @param string $type
     *
     * @return string[]
     *
     * @throws GuzzleException
     */
    private function getLinkHref($type)
    {
        if (!isset($this->feedUrls[$type])) {
            if (false === $this->findFeedUrls()) {
                return [];
            }
        }

        if (!isset($this->feedUrls[$type])) {
            return [];
        }

        return $this->feedUrls[$type];
    }

    /**
     * @return array|bool
     *
     * @throws GuzzleException
     */
    private function findFeedUrls()
    {
        $rootWebPage = $this->retrieveRootWebPage();
        if (empty($rootWebPage)) {
            return false;
        }

        libxml_use_internal_errors(true);

        $feedUrls = [];
        $supportedFeedTypes = $this->supportedFeedTypes;
        try {
            $rootWebPage
                ->find('link[rel=alternate]')
                ->each(
                    function ($index, \DOMElement $domElement) use (&$feedUrls, $supportedFeedTypes) {
                        unset($index);

                        foreach ($supportedFeedTypes as $supportedFeedType) {
                            if ($domElement->getAttribute('type') == $supportedFeedType) {
                                if (!isset($feedUrls[$supportedFeedType])) {
                                    $feedUrls[$supportedFeedType] = [];
                                }

                                $hasSupportedFeedType = in_array(
                                    $domElement->getAttribute('href'),
                                    $feedUrls[$supportedFeedType]
                                );

                                if (!$hasSupportedFeedType) {
                                    $feedUrls[$supportedFeedType][] =
                                        $domElement->getAttribute('href');
                                }
                            }
                        }
                    }
                );
        } catch (ParseException $parseException) {
            // Invalid XML
        }

        libxml_use_internal_errors(false);
        return $this->feedUrls = $feedUrls;
    }

    /**
     * @return WebPageInterface
     *
     * @throws GuzzleException
     */
    private function retrieveRootWebPage()
    {
        $webPage = null;

        try {
            /* @var WebPage $webPage */
            $webPage = $this->webResourceRetriever->retrieve(new Request('GET', $this->rootUrl));
        } catch (\Exception $exception) {
        }

        return $webPage;
    }
}
