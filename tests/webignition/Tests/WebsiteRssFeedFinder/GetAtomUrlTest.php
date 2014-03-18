<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetAtomUrlTest extends GetFeedUrlTest { 

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