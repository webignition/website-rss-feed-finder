<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

abstract class GetFeedUrlTest extends BaseTest {
    
    public function setUp() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(get_class($this), $this->getName() . '/HttpResponses')));
    }    
       
    
}