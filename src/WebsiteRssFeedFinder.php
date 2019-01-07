<?php

namespace webignition\WebsiteRssFeedFinder;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use QueryPath\Exception as QueryPathException;
use QueryPath\ParseException;
use webignition\NormalisedUrl\NormalisedUrl;
use webignition\WebResource\Retriever as WebResourceRetriever;
use webignition\WebResource\WebPage\WebPage;

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

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;

        $this->webResourceRetriever = new WebResourceRetriever(
            $this->httpClient,
            WebPage::getModelledContentTypeStrings(),
            false
        );
    }

    public function setRootUrl(string $url)
    {
        $this->rootUrl = new NormalisedUrl($url);
        $this->feedUrls = [];
    }

    /**
     * @return array
     *
     * @throws QueryPathException
     */
    public function getRssFeedUrls(): array
    {
        return $this->getLinkHref('application/rss+xml');
    }

    /**
     * @return array
     *
     * @throws QueryPathException
     */
    public function getAtomFeedUrls(): array
    {
        return $this->getLinkHref('application/atom+xml');
    }

    /**
     * @param string $type
     * @return array
     *
     * @throws QueryPathException
     */
    private function getLinkHref(string $type): array
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
     * @throws QueryPathException
     */
    private function findFeedUrls()
    {
        /* @var WebPage $rootWebPage */
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

    private function retrieveRootWebPage(): ?WebPage
    {
        $webPage = null;

        try {
            /* @var WebPage $webPage */
            $webPage = $this->webResourceRetriever->retrieve(new Request('GET', $this->rootUrl));
        } catch (\Exception $exception) {
        } catch (GuzzleException $guzzleException) {
        }

        return $webPage;
    }
}
