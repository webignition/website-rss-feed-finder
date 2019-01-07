<?php

namespace webignition\WebsiteRssFeedFinder;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use webignition\NormalisedUrl\NormalisedUrl;
use webignition\WebPageInspector\WebPageInspector;
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

    public function getRssFeedUrls(): array
    {
        return $this->getLinkHref('application/rss+xml');
    }

    public function getAtomFeedUrls(): array
    {
        return $this->getLinkHref('application/atom+xml');
    }

    private function getLinkHref(string $type): array
    {
        if (!isset($this->feedUrls[$type])) {
            $this->findFeedUrls();
        }

        if (!isset($this->feedUrls[$type])) {
            return [];
        }

        return $this->feedUrls[$type];
    }

    /** @noinspection PhpDocMissingThrowsInspection */
    private function findFeedUrls()
    {
        /* @var WebPage $rootWebPage */
        $rootWebPage = $this->retrieveRootWebPage();
        if (empty($rootWebPage)) {
            return;
        }

        $feedUrls = [];
        $supportedFeedTypes = $this->supportedFeedTypes;

        /** @noinspection PhpUnhandledExceptionInspection */
        $inspector = new WebPageInspector($rootWebPage);

        /* @var \DOMElement[] $linkRelAlternativeElements */
        $linkRelAlternativeElements = $inspector->querySelectorAll('link[rel=alternate]');

        foreach ($linkRelAlternativeElements as $linkRelAlternativeElement) {
            foreach ($supportedFeedTypes as $supportedFeedType) {
                if ($linkRelAlternativeElement->getAttribute('type') == $supportedFeedType) {
                    if (!isset($feedUrls[$supportedFeedType])) {
                        $feedUrls[$supportedFeedType] = [];
                    }

                    $hasSupportedFeedType = in_array(
                        $linkRelAlternativeElement->getAttribute('href'),
                        $feedUrls[$supportedFeedType]
                    );

                    if (!$hasSupportedFeedType) {
                        $feedUrls[$supportedFeedType][] =
                            $linkRelAlternativeElement->getAttribute('href');
                    }
                }
            }
        }

        $this->feedUrls = $feedUrls;
    }

    private function retrieveRootWebPage(): ?WebPage
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
