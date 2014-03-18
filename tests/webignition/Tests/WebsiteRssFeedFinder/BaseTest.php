<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

use Guzzle\Http\Client as HttpClient;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {
    
        
    /**
     *
     * @var \Guzzle\Http\Client
     */
    private $httpClient = null;       
    
    
    /**
     *
     * @var \webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder
     */
    private $feedFinder = null;
    
    
    /**
     * 
     * @return \Guzzle\Http\Client
     */
    protected function getHttpClient() {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient();
            $this->httpClient->addSubscriber(new \Guzzle\Plugin\History\HistoryPlugin());
        }
        
        return $this->httpClient;
    }   
    
    
    /**
     * 
     * @return \Guzzle\Plugin\History\HistoryPlugin|null
     */
    protected function getHttpHistory() {
        $listenerCollections = $this->getHttpClient()->getEventDispatcher()->getListeners('request.sent');
        
        foreach ($listenerCollections as $listener) {
            if ($listener[0] instanceof \Guzzle\Plugin\History\HistoryPlugin) {
                return $listener[0];
            }
        }
        
        return null;     
    }   
        
    
    protected function setHttpFixtures($fixtures) {
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        
        foreach ($fixtures as $fixture) {
            $plugin->addResponse($fixture);
        }
         
        $this->getHttpClient()->addSubscriber($plugin);              
    }
    
    
    protected function getHttpFixtures($path) {
        $fixtures = array();        
        $fixturesDirectory = new \DirectoryIterator($path);
        
        $fixturePathnames = array();
        
        foreach ($fixturesDirectory as $directoryItem) {
            if ($directoryItem->isFile()) { 
                $fixturePathnames[] = $directoryItem->getPathname();
            }
        }
        
        sort($fixturePathnames);
        
        foreach ($fixturePathnames as $fixturePathname) {
                $fixtures[] = \Guzzle\Http\Message\Response::fromMessage(file_get_contents($fixturePathname));            
        }
        
        return $fixtures;
    } 
    

    /**
     *
     * @param string $testName
     * @return string
     */
    protected function getFixturesDataPath($className, $testName = null) {        
        $path = __DIR__ . '/../../../fixtures/' . str_replace('\\', DIRECTORY_SEPARATOR, $className);
        
        if (!is_null($testName)) {
            $path .=  '/' . $testName;
        }
        
        return $path;
    }
    
    
    
    /**
     * 
     * @return \webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder
     */
    protected function getFeedFinder() {
        if (is_null($this->feedFinder)) {
            $this->feedFinder = new \webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();
            $this->feedFinder->getConfiguration()->setBaseRequest($this->getHttpClient()->get());
        }
        
        return $this->feedFinder;
    }
    
    
    /**
     * 
     * @param array $items Collection of http messages and/or curl exceptions
     * @return array
     */
    protected function buildHttpFixtureSet($items) {
        $fixtures = array();
        
        foreach ($items as $item) {
            switch ($this->getHttpFixtureItemType($item)) {
                case 'httpMessage':
                    $fixtures[] = \Guzzle\Http\Message\Response::fromMessage($item);
                    break;
                
                case 'curlException':
                    $fixtures[] = $this->getCurlExceptionFromCurlMessage($item);                    
                    break;
                
                default:
                    throw new \LogicException();
            }
        }
        
        return $fixtures;
    }
    
    
    /**
     * 
     * @param string $item
     * @return string
     */
    private function getHttpFixtureItemType($item) {
        if (substr($item, 0, strlen('HTTP')) == 'HTTP') {
            return 'httpMessage';
        }
        
        return 'curlException';
    }     
    
    
}