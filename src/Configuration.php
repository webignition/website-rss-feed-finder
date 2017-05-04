<?php
namespace webignition\WebsiteRssFeedFinder;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\Request as GuzzleRequest;
use webignition\NormalisedUrl\NormalisedUrl;

/**
 *
 */
class Configuration
{
    /**
     *
     * @var GuzzleRequest
     */
    private $baseRequest = null;

    /**
     *
     * @var \webignition\NormalisedUrl\NormalisedUrl
     */
    private $rootUrl = null;

    /**
     *
     * @var array
     */
    private $cookies = array();

    /**
     *
     * @param array $cookies
     * @return self
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     *
     * @param GuzzleRequest $request
     * @return self
     */
    public function setBaseRequest(GuzzleRequest $request)
    {
        $this->baseRequest = $request;

        return $this;
    }

    /**
     *
     * @return GuzzleRequest $request
     */
    public function getBaseRequest()
    {
        if (is_null($this->baseRequest)) {
            $client = new GuzzleClient;
            $this->baseRequest = $client->get();
        }

        return $this->baseRequest;
    }

    /**
     *
     * @param string $rootUrl
     * @return self
     */
    public function setRootUrl($rootUrl)
    {
        $this->rootUrl = new NormalisedUrl($rootUrl);

        return $this;
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
