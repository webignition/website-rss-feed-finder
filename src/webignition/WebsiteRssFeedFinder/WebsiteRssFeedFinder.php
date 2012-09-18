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
     * @var \webignition\Http\Client\Client
     */
    private $httpClient = null;
    
    
    /**
     *
     * @var \webignition\NormalisedUrl\NormalisedUrl 
     */
    private $rootUrl = null;
    
    
    /**
     *
     * @var \webignition\WebsiteSitemapFinder\XmlSitemapIdentifier
     */
    private $sitemapIdentifier = null;
    
//    private $allowedWebPageContentTypes = array(
//        'text/html',
//        'application/xhtml+xml'
//    );

    
    
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
     * @return string
     */
    public function getRootUrl() {
        return (is_null($this->rootUrl)) ? '' : (string)$this->rootUrl;
    }
    
    
    /**
     *
     * @param \webignition\Http\Client\Client $client 
     */
    public function setHttpClient(\webignition\Http\Client\Client $client) {
        $this->httpClient = $client;
    }
    
    
    /**
     *
     * @return \webignition\Http\Client\Client 
     */
    private function getHttpClient() {
        if (is_null($this->httpClient)) {
            $this->httpClient = new \webignition\Http\Client\Client();
            $this->httpClient->redirectHandler()->enable();
        }
        
        return $this->httpClient;
    }

    
    /**
     *
     * @return string
     */
    public function getRssFeedUrl() {
        $rootWebPage = $this->getRootWebPage();
        if ($rootWebPage == false) {
            return false;
        }
        
        libxml_use_internal_errors(true);
        
        $rssFeedUrl = null;        
        try {
            @$rootWebPage->find('link[rel=alternate]')->each(function ($index, \DOMElement $domElement) use (&$rssFeedUrl) {
                if ($domElement->getAttribute('type') == 'application/rss+xml') {
                    $rssFeedUrl = $domElement->getAttribute('href');
                }
            });           
        } catch (QueryPath\ParseException $parseException) {
            // Invalid XML
        }
        
        return $rssFeedUrl;
    }
    
    
    /**
     * Get the URL where we expect to find the robots.txt file
     * 
     * @return string
     */
    public function getExpectedRobotsTxtFileUrl() {
        if ($this->rootUrl->getRoot() == '') {            
            return (string)$this->rootUrl . self::DEFAULT_SITEMAP_TXT_FILE_NAME;
        }
        
        $rootUrl = new NormalisedUrl($this->rootUrl->getRoot());        
        $rootUrl->setPath('/'.self::ROBOTS_TXT_FILE_NAME);
        
        return (string)$rootUrl;
    }  
    
    
    private function getPossibleSitemapUrls() {
       $sitemapUrlFromRobotsTxt = $this->findSitemapUrlFromRobotsTxt();
       if ($sitemapUrlFromRobotsTxt === false) {
           return array(
               $this->getDefaultSitemapXmlUrl(),
               $this->getDefaultSitemapTxtUrl()
           );
       }
       
       return array($sitemapUrlFromRobotsTxt);
    }
    
    
    /**
     *
     * @return string
     */
    private function getDefaultSitemapXmlUrl() {
        $absoluteUrlDeriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
               '/' . self::DEFAULT_SITEMAP_XML_FILE_NAME,
               $this->getRootUrl()
        );
        
        return (string)$absoluteUrlDeriver->getAbsoluteUrl();
    }
    
    
    /**
     *
     * @return string
     */
    private function getDefaultSitemapTxtUrl() {
        $absoluteUrlDeriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
               '/' . self::DEFAULT_SITEMAP_TXT_FILE_NAME,
               $this->getRootUrl()
        );
        
        return (string)$absoluteUrlDeriver->getAbsoluteUrl();
    } 
    
    
    private function findSitemapUrlFromRobotsTxt() {
        $robotsTxtParser = new \webignition\RobotsTxt\File\Parser();
        $robotsTxtParser->setSource($this->getRobotsTxtContent());        
        $robotsTxtFile = $robotsTxtParser->getFile();
        
        if ($robotsTxtFile->directiveList()->containsField('sitemap')) {
            return (string)$robotsTxtFile->directiveList()->filter(array('field', 'sitemap'))->first()->getValue();         
        }
        
        return false;
    }
    
    
    /**
     *
     * @return WebPage 
     */
    private function getRootWebPage() {        
        $request = new \HttpRequest($this->getRootUrl());        
        $response = $this->getHttpClient()->getResponse($request);       
        
        if (!$response->getResponseCode() == 200) {
            return false;
        }
        
        try {
            $webPage = new WebPage();
            $webPage->setContentType($response->getHeader('content-type'));
            $webPage->setContent($response->getBody());

            return $webPage;            
        } catch (\webignition\WebResource\Exception $exception) {
            // Invalid content type (is not the URL of a web page)
            return false;
        }
    }
    
    
    /**
     *
     * @return \webignition\WebsiteSitemapFinder\SitemapIdentifier
     */
    private function sitemapIdentifier() {
        if (is_null($this->sitemapIdentifier)) {
            $this->sitemapIdentifier = new \webignition\WebsiteSitemapFinder\SitemapIdentifier();
            $this->sitemapIdentifier->setHttpClient($this->getHttpClient());
        }
        
        return $this->sitemapIdentifier;
    }
    
}