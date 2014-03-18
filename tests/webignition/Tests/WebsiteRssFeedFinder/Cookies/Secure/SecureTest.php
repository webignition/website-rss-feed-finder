<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Secure;

use webignition\Tests\WebsiteRssFeedFinder\Cookies\CookiesTest;

abstract class SecureTest extends CookiesTest {
    
    protected function getCookies() {
        return array(
            array(
                'domain' => '.example.com',
                'name' => 'foo',
                'value' => 'bar',
                'secure' => true
            )
        );
    }
    
}