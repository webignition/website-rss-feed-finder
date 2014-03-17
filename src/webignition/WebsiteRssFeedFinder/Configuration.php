<?php
namespace webignition\WebsiteRssFeedFinder;

use webignition\NormalisedUrl\NormalisedUrl;

/**
 *  
 */
class Configuration {    
    
    /**
     *
     * @var \Guzzle\Http\Message\Request
     */
    private $baseRequest = null;
    
    
    /**
     *
     * @var \webignition\NormalisedUrl\NormalisedUrl 
     */
    private $rootUrl = null;   
    
    
    /**
     * 
     * @param \Guzzle\Http\Message\Request $request
     * @return \webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder
     */
    public function setBaseRequest(\Guzzle\Http\Message\Request $request) {
        $this->baseRequest = $request;
        return $this;
    }
    
    
    
    /**
     * 
     * @return \Guzzle\Http\Message\Request $request
     */
    public function getBaseRequest() {
        if (is_null($this->baseRequest)) {
            $client = new \Guzzle\Http\Client;            
            $this->baseRequest = $client->get();
        }
        
        return $this->baseRequest;
    } 
    
    
    /**
     *
     * @param string $rootUrl
     * @return \webignition\WebsiteSitemapFinder\WebsiteSitemapFinder 
     */
    public function setRootUrl($rootUrl) {        
        $this->rootUrl = new NormalisedUrl($rootUrl);
        return $this;
    }
    
    
    /**
     *
     * @return string|null
     */
    public function getRootUrl() {
        return $this->rootUrl;
    }   
    
}