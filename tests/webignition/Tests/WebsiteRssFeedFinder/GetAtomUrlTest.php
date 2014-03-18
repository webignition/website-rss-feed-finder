<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetAtomUrlTest extends GetFeedUrlTest { 

    public function testGetSingleAtomUrl() {
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl('http://www.example.com/');
        
        $this->assertEquals(array('http://example.com/atom.xml'), $finder->getAtomFeedUrls());        
    }    
    
    
    public function testGetMultipleAtomUrls() {
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl('http://www.example.com/');
        
        $this->assertEquals(array(
            'http://example.com/atom-1.xml',
            'http://example.com/atom-2.xml'            
        ), $finder->getAtomFeedUrls());        
    }        
    
}