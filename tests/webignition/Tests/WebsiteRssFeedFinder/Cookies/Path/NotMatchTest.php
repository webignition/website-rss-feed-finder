<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Path;

class NotMatchTest extends PathTest {   
    
    protected function getCookies() {
        return array(
            array(
                'domain' => '.example.com',
                'name' => 'foo',
                'value' => 'bar',
                'path' => '/bar'
            )
        );
    }

    protected function getExpectedRequestsOnWhichCookiesShouldBeSet() {
        return array();
    }

    protected function getExpectedRequestsOnWhichCookiesShouldNotBeSet() {        
        return $this->getAllSentHttpRequests();
    }    
}