<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetRootWebPageErrorTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__CLASS__, $this->getName() . '/HttpResponses')));
    }       

    public function testHandleHttpClientError() {        
        $finder = $this->getFeedFinder();
        $finder->setRootUrl('http://example.com/');
        
        $this->assertNull($finder->getRssFeedUrls());        
    } 
    
    public function testHandleHttpServerError() {        
        $finder = $this->getFeedFinder();
        $finder->setRootUrl('http://example.com/');
        
        $this->assertNull($finder->getRssFeedUrls());        
    }     
    
    
}