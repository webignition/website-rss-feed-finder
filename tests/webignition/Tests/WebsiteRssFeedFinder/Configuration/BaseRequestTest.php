<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Configuration;

class BaseRequestTest extends ConfigurationTest {
    
    public function testGetDefaultBaseRequest() {             
        $this->assertInstanceOf('\Guzzle\Http\Message\Request', $this->configuration->getBaseRequest());
    }
    
    public function testSetReturnsSelf() {
        $this->assertEquals($this->configuration, $this->configuration->setBaseRequest($this->getHttpClient()->get()));
    }
    
    public function testSetGetBaseRequest() {        
        $baseRequest = $this->getHttpClient()->get();
        $baseRequest->setAuth('example_user', 'example_password');
        
        $this->configuration->setBaseRequest($baseRequest);
        
        $this->assertEquals('example_user', $this->configuration->getBaseRequest()->getUsername());
        $this->assertEquals($baseRequest->getUsername(), $this->configuration->getBaseRequest()->getUsername());
    }
    
}