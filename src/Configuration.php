<?php
namespace webignition\WebsiteRssFeedFinder;

use GuzzleHttp\Client as HttpClient;
use webignition\NormalisedUrl\NormalisedUrl;

class Configuration
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var NormalisedUrl
     */
    private $rootUrl = null;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
    }

    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     *
     * @param string $rootUrl
     */
    public function setRootUrl($rootUrl)
    {
        $rootUrl = trim($rootUrl);

        $this->rootUrl = (empty($rootUrl))
            ? null
            : new NormalisedUrl($rootUrl);
    }

    /**
     *
     * @return string|null
     */
    public function getRootUrl()
    {
        return $this->rootUrl;
    }
}
