<?php

namespace webignition\WebsiteRssFeedFinder;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\UriInterface;
use webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver;
use webignition\Uri\Normalizer;
use webignition\Uri\Uri;
use webignition\WebPageInspector\WebPageInspector;
use webignition\WebResource\Retriever as WebResourceRetriever;
use webignition\WebResource\WebPage\WebPage;

class WebsiteRssFeedFinder
{
    const TYPE_RSS = 'application/rss+xml';
    const TYPE_ATOM = 'application/atom+xml';

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
    private $feedUris = [];

    /**
     * @var string[]
     */
    private $supportedFeedTypes = [
        self::TYPE_RSS,
        self::TYPE_ATOM,
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

        foreach ($this->supportedFeedTypes as $feedType) {
            $this->feedUris[$feedType] = [];
        }
    }

    public function setRootUrl(UriInterface $uri)
    {
        $this->rootUri = Normalizer::normalize($uri);

        foreach ($this->supportedFeedTypes as $feedType) {
            $this->feedUris[$feedType] = [];
        }
    }

    /**
     * @return UriInterface[]
     */
    public function getRssFeedUrls(): array
    {
        return $this->getLinkHref(self::TYPE_RSS);
    }

    /**
     * @return UriInterface[]
     */
    public function getAtomFeedUrls(): array
    {
        return $this->getLinkHref(self::TYPE_ATOM);
    }

    /**
     * @param string $type
     *
     * @return UriInterface[]
     */
    private function getLinkHref(string $type): array
    {
        if (empty($this->feedUris[$type])) {
            $this->findFeedUrls();
        }

        return array_values($this->feedUris[$type]);
    }

    /** @noinspection PhpDocMissingThrowsInspection */
    private function findFeedUrls()
    {
        /* @var WebPage $webPage */
        $webPage = $this->retrieveWebPage();
        if (empty($webPage)) {
            return;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $inspector = new WebPageInspector($webPage);

        /* @var \DOMElement[] $linkRelAlternativeElements */
        $linkRelAlternativeElements = $inspector->querySelectorAll('link[rel=alternate]');

        foreach ($linkRelAlternativeElements as $linkRelAlternativeElement) {
            foreach ($this->supportedFeedTypes as $supportedFeedType) {
                $feedType = $linkRelAlternativeElement->getAttribute('type');

                if ($feedType === $supportedFeedType) {
                    $hrefValue = $linkRelAlternativeElement->getAttribute('href');

                    $feedUri = AbsoluteUrlDeriver::derive($this->rootUri, new Uri($hrefValue));
                    $feedUriString = (string) $feedUri;

                    $this->feedUris[$supportedFeedType][$feedUriString] = $feedUri;
                }
            }
        }
    }

    private function retrieveWebPage(): ?WebPage
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
