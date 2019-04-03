<?php

namespace webignition\WebsiteRssFeedFinder;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\UriInterface;
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
     * @var UriInterface
     */
    private $rootUri = null;

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

    public function setRootUrl(UriInterface $uri)
    {
        $this->rootUri = \Normalizer::normalize($uri);
        $this->feedUrls = [];
    }

    /**
     * @return string[]
     */
    public function getRssFeedUrls(): array
    {
        return $this->getLinkHref('application/rss+xml');
    }

    /**
     * @return string[]
     */
    public function getAtomFeedUrls(): array
    {
        return $this->getLinkHref('application/atom+xml');
    }

    /**
     * @param string $type
     *
     * @return string[]
     */
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

        /** @noinspection PhpUnhandledExceptionInspection */
        $inspector = new WebPageInspector($rootWebPage);

        /* @var \DOMElement[] $linkRelAlternativeElements */
        $linkRelAlternativeElements = $inspector->querySelectorAll('link[rel=alternate]');

        foreach ($linkRelAlternativeElements as $linkRelAlternativeElement) {
            foreach ($this->supportedFeedTypes as $supportedFeedType) {
                if ($linkRelAlternativeElement->getAttribute('type') == $supportedFeedType) {
                    if (!isset($feedUrls[$supportedFeedType])) {
                        $feedUrls[$supportedFeedType] = [];
                    }

                    $hrefValue = $linkRelAlternativeElement->getAttribute('href');

                    if (!in_array($hrefValue, $feedUrls[$supportedFeedType])) {
                        $feedUrls[$supportedFeedType][] = $hrefValue;
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
            $webPage = $this->webResourceRetriever->retrieve(new Request('GET', $this->rootUri));
        } catch (\Exception $exception) {
        }

        return $webPage;
    }
}
