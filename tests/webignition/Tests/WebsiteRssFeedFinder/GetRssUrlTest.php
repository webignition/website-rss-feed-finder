<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

class GetRssUrlTest extends GetFeedUrlTest {      

    public function testGetSingleRssUrl() {        
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl('http://codinghorror.com/blog/');
        
        $this->assertEquals(array('http://feeds.feedburner.com/codinghorror/'), $finder->getRssFeedUrls());        
    } 
    
    public function testGetMultipleRssUrls() {        
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl('http://korben.info/');
        
        $this->assertEquals(array(
            'http://korben.info/feed',
            'http://korben.info/wp-content/plugins/nextgen-gallery/xml/media-rss.php'
        ), $finder->getRssFeedUrls()); 
    } 
    
    
    public function testForHttpAuthProtectedSite() {
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl('http://example.com/');        
        $finder->getConfiguration()->getBaseRequest()->setAuth('example', 'password', 'any');
        
        $this->assertEquals(array(
            'http://example.com/feed.xml'
        ), $finder->getRssFeedUrls());         
    }
    
    
}