<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\Atom;

use webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls\GetFeedUrlsTest;

class GetUrlsTest extends GetFeedUrlsTest { 

    public function testGetSingleAtomUrl() {        
        $this->assertEquals(array('http://example.com/atom-1.xml'), $this->getFeedFinder()->getAtomFeedUrls());        
    }    
    
    
    public function testGetMultipleAtomUrls() {        
        $this->assertEquals(array(
            'http://example.com/atom-1.xml',
            'http://example.com/atom-2.xml'            
        ), $this->getFeedFinder()->getAtomFeedUrls());        
    }        
    
}