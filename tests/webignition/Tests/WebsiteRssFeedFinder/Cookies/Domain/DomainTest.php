<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Cookies\Domain;

use webignition\Tests\WebsiteRssFeedFinder\Cookies\CookiesTest;

abstract class DomainTest extends CookiesTest {   
    
    protected function getRootUrl() {
        return 'http://example.com';
    }   
}