<?php
namespace webignition\WebsiteRssFeedFinder;

use webignition\NormalisedUrl\NormalisedUrl;
use webignition\WebResource\WebPage\WebPage;

/**
 *  
 */
class WebsiteRssFeedFinder {    
    
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
     * @var WebPage
     */
    private $rootWebPage = null;
    
    
    /**
     * Collection of feed urls
     * 
     * @var array
     */
    private $feedUrls = array(); 
    
    
    private $supportedFeedTypes = array(
        'application/rss+xml',
        'application/atom+xml'
    );
    
    
    /**
     * 
     * @param \Guzzle\Http\Message\Request $request
     */
    public function setBaseRequest(\Guzzle\Http\Message\Request $request) {
        $this->baseRequest = $request;
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
        $this->feedUrls = array();
        $this->rootWebPage = null;
        return $this;
    }
    
    
    /**
     *
     * @return string
     */
    public function getRootUrl() {
        return (is_null($this->rootUrl)) ? '' : (string)$this->rootUrl;
    }

    
    /**
     *
     * @return array
     */
    public function getRssFeedUrls() {
        return $this->getLinkHref('alternate', 'application/rss+xml');
    }
    
    
    /**
     *
     * @return array
     */
    public function getAtomFeedUrls() {
        return $this->getLinkHref('alternate', 'application/atom+xml');
    }
    
    
    /**
     *
     * @param string $rel
     * @param string $type
     * @return string 
     */
    private function getLinkHref($rel, $type) {        
        if (!isset($this->feedUrls[$rel]) || !isset($this->feedUrls[$rel][$type])) {            
            if (!$this->findFeedUrls()) {
                return null;
            }
        }
        
        if (!isset($this->feedUrls[$rel]) || !isset($this->feedUrls[$rel][$type])) { 
            return null;
        }
        
        return $this->feedUrls[$rel][$type];
    }
    
    
    private function findFeedUrls() {        
        $rootWebPage = $this->getRootWebPage();
        if ($rootWebPage == false) {
            return false;
        }        
        
        libxml_use_internal_errors(true);
        
        $feedUrls = array();
        $supportedFeedTypes = $this->supportedFeedTypes;
        try {            
            $rootWebPage->find('link[rel=alternate]')->each(function ($index, \DOMElement $domElement) use (&$feedUrls, $supportedFeedTypes) {                
                foreach ($supportedFeedTypes as $supportedFeedType) {                
                    if ($domElement->getAttribute('type') == $supportedFeedType) {
                        if (!isset($feedUrls['alternate'])) {
                            $feedUrls['alternate'] = array();
                        }
                        
                        if (!isset($feedUrls['alternate'][$supportedFeedType])) {
                            $feedUrls['alternate'][$supportedFeedType] = array();
                        }
                        
                        if (!in_array($domElement->getAttribute('href'), $feedUrls['alternate'][$supportedFeedType])) {
                            $feedUrls['alternate'][$supportedFeedType][] = $domElement->getAttribute('href');
                        }
                    }                    
                }
            });           
        } catch (QueryPath\ParseException $parseException) {
            // Invalid XML              
        }
        
        libxml_use_internal_errors(false);        
        return $this->feedUrls = $feedUrls;
    }
    
    
    /**
     *
     * @return WebPage 
     */
    private function getRootWebPage() {        
        if (is_null($this->rootWebPage)) {
            $this->rootWebPage = $this->retrieveRootWebPage();
        }
        
        return $this->rootWebPage;
    }
    
    
    /**
     *
     * @return boolean|\webignition\WebResource\WebPage\WebPage 
     */
    private function retrieveRootWebPage() {
        $request = clone $this->getBaseRequest();
        $request->setUrl($this->getRootUrl());
        
        try {
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\RequestException $requestException) {
            return false;
        }        
        
        try {
            $webPage = new WebPage();
            $webPage->setContentType($response->getHeader('content-type'));
            $webPage->setContent($response->getBody(true));

            return $webPage;            
        } catch (\webignition\WebResource\Exception $exception) {
            // Invalid content type (is not the URL of a web page)
            return false;
        }        
    }    
    
}