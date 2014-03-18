<?php

namespace webignition\Tests\WebsiteRssFeedFinder\GetFeedUrls;

class GetNullForMissingFeedUrlTest extends GetFeedUrlTest {    

    public function testGetNullForMissingFeedUrl() {
        // Note: each of the below uses a different root web page
        // The first root web page lacks atom urls
        // The second root web page lacks rss urls
        $this->assertNull($this->getFeedFinder()->reset()->getAtomFeedUrls());               
        $this->assertNull($this->getFeedFinder()->reset()->getRssFeedUrls());                          
    }    
    
}