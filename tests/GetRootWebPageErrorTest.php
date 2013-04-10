<?php

class GetRootWebPageErrorTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__CLASS__, $this->getName() . '/HttpResponses')));
    }       

    public function testHandleHttpClientError() {        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($this->getHttpClient());
        $finder->setRootUrl('http://example.com/');
        
        $this->assertNull($finder->getRssFeedUrls());        
    } 
    
    public function testHandleHttpServerError() {        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($this->getHttpClient());
        $finder->setRootUrl('http://example.com/');
        
        $this->assertNull($finder->getRssFeedUrls());        
    }     
    
    
}