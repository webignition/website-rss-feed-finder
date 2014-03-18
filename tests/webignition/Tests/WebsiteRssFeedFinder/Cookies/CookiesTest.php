<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies;

use webignition\Tests\WebsiteRssFeedFinder\BaseTest;

abstract class CookiesTest extends BaseTest {
    
    /**
     * 
     * @return array
     */
    abstract protected function getCookies();
    
    /**
     * @return string
     */
    abstract protected function getRootUrl();
    
    /**
     * 
     * @return \Guzzle\Http\Message\RequestInterface[]
     */    
    abstract protected function getExpectedRequestsOnWhichCookiesShouldBeSet();
    
    /**
     * 
     * @return \Guzzle\Http\Message\RequestInterface[]
     */    
    abstract protected function getExpectedRequestsOnWhichCookiesShouldNotBeSet();     
    
    
    public function setUp() {
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            'HTTP/1.0 200'
        )));
        
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl($this->getRootUrl());        
        $finder->getConfiguration()->setCookies($this->getCookies());
        $finder->getAtomFeedUrls();
    }
    
    
    public function testCookiesAreSetOnExpectedRequests() {
        foreach ($this->getExpectedRequestsOnWhichCookiesShouldBeSet() as $request) {
            $this->assertEquals($this->getExpectedCookieValues(), $request->getCookies());
        }
    }
    
    public function testCookiesAreNotSetOnExpectedRequests() {        
        foreach ($this->getExpectedRequestsOnWhichCookiesShouldNotBeSet() as $request) {            
            $this->assertEquals(array(), $request->getCookies());
        }
    }  
    
    
    /**
     * 
     * @return array
     */
    private function getExpectedCookieValues() {
        $nameValueArray = array();
        
        foreach ($this->getCookies() as $cookie) {
            $nameValueArray[$cookie['name']] = $cookie['value'];
        }
        
        return $nameValueArray;
    }  
    
    
    /**
     * 
     * @return \Guzzle\Http\Message\Request[]
     */
    protected function getAllSentHttpRequests() {
        $requests = array();
        
        foreach ($this->getHttpHistory()->getAll() as $httpTransaction) {
            $requests[] = $httpTransaction['request'];
        }
        
        return $requests;
    }
    
    
    protected function getLastHttpRequest() {        
        $requests = $this->getAllSentHttpRequests();
        return $requests[count($requests) - 1];
    }
    
    protected function getFirstHttpRequest() {
        $requests = $this->getAllSentHttpRequests();
        return $requests[0];        
    }
    
    
}