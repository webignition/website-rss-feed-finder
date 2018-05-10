<?php

namespace webignition\WebsiteRssFeedFinder;

use QueryPath\Exception as QueryPathException;
use QueryPath\ParseException;
use webignition\WebResource\Service\Configuration as WebResourceServiceConfiguration;
use webignition\WebResource\WebPage\WebPage;
use webignition\WebResource\Service\Service as WebResourceService;
use webignition\WebResource\WebResource;

class WebsiteRssFeedFinder
{
    /**
     * @var WebPage
     */
    private $rootWebPage = null;

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
     * @var Configuration
     */
    private $configuration;

    /**
     * @var WebResourceService
     */
    private $webResourceService;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        $webResourceServiceConfiguration = new WebResourceServiceConfiguration([
            WebResourceServiceConfiguration::CONFIG_ALLOW_UNKNOWN_RESOURCE_TYPES => false,
            WebResourceServiceConfiguration::CONFIG_KEY_CONTENT_TYPE_WEB_RESOURCE_MAP => [
                'text/html' => WebPage::class,
            ],
            WebResourceServiceConfiguration::CONFIG_KEY_HTTP_CLIENT => $this->configuration->getHttpClient(),
            WebResourceServiceConfiguration::CONFIG_ALLOW_UNKNOWN_RESOURCE_TYPES => true,
        ]);

        $this->webResourceService = new WebResourceService();
        $this->webResourceService->setConfiguration($webResourceServiceConfiguration);
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return string[]
     * @throws QueryPathException
     */
    public function getRssFeedUrls()
    {
        return $this->getLinkHref('application/rss+xml');
    }

    /**
     * @return string[]
     *
     * @throws QueryPathException
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
     * @throws QueryPathException
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
     * @throws QueryPathException
     */
    private function findFeedUrls()
    {
        $rootWebPage = $this->getRootWebPage();
        if (!$rootWebPage instanceof WebPage) {
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
     * @return WebPage
     */
    private function getRootWebPage()
    {
        if (is_null($this->rootWebPage)) {
            $this->rootWebPage = $this->retrieveRootWebPage();
        }

        return $this->rootWebPage;
    }

    /**
     * @return WebResource
     */
    private function retrieveRootWebPage()
    {
        $httpClient = $this->configuration->getHttpClient();
        $request = $httpClient->createRequest('GET', $this->configuration->getRootUrl());

        try {
            return $this->webResourceService->get($request);
        } catch (\Exception $exception) {
            return null;
        }
    }
}
