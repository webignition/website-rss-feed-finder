<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

abstract class GetFeedUrlTest extends BaseTest {
    
    const ROOT_WEB_PAGE_URL = 'http://www.example.com/';
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(get_class($this), $this->getName() . '/HttpResponses')));
        
        $finder = $this->getFeedFinder();
        $finder->getConfiguration()->setRootUrl(self::ROOT_WEB_PAGE_URL);        
    }    
       
    
}