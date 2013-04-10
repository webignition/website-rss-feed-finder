<?php

use Guzzle\Http\Client as HttpClient;

class SetHttpClientTest extends PHPUnit_Framework_TestCase {

    public function testSetHttpClient() {
        $httpClient = new HttpClient();
        
        $finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
        $finder->setHttpClient($httpClient);    
    }    
    
}