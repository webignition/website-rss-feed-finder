<?php

namespace webignition\Tests\WebsiteRssFeedFinder;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\History\HistoryPlugin;
use Guzzle\Plugin\Mock\MockPlugin;
use webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var HttpClient
     */
    private $httpClient = null;

    /**
     *
     * @var WebsiteRssFeedFinder
     */
    private $feedFinder = null;

    /**
     *
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient();
            $this->httpClient->addSubscriber(new HistoryPlugin());
        }

        return $this->httpClient;
    }

    /**
     *
     * @return HistoryPlugin|null
     */
    protected function getHttpHistory()
    {
        $listenerCollections = $this->getHttpClient()->getEventDispatcher()->getListeners('request.sent');

        foreach ($listenerCollections as $listener) {
            if ($listener[0] instanceof HistoryPlugin) {
                return $listener[0];
            }
        }

        return null;
    }

    protected function setHttpFixtures($fixtures)
    {
        $plugin = new MockPlugin();

        foreach ($fixtures as $fixture) {
            $plugin->addResponse($fixture);
        }

        $this->getHttpClient()->addSubscriber($plugin);
    }

    protected function getHttpFixtures($path)
    {
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
                $fixtures[] = Response::fromMessage(file_get_contents($fixturePathname));
        }

        return $fixtures;
    }

    /**
     *
     * @param string $testName
     * @return string
     */
    protected function getFixturesDataPath($className, $testName = null)
    {
        $path = __DIR__ . '/../../../fixtures/' . str_replace('\\', DIRECTORY_SEPARATOR, $className);

        if (!is_null($testName)) {
            $path .=  '/' . $testName;
        }

        return $path;
    }

    /**
     *
     * @return WebsiteRssFeedFinder
     */
    protected function getFeedFinder()
    {
        if (is_null($this->feedFinder)) {
            $this->feedFinder = new WebsiteRssFeedFinder();
            $this->feedFinder->getConfiguration()->setBaseRequest($this->getHttpClient()->get());
        }

        return $this->feedFinder;
    }

    /**
     *
     * @param array $items Collection of http messages and/or curl exceptions
     * @return array
     */
    protected function buildHttpFixtureSet($items)
    {
        $fixtures = array();

        foreach ($items as $item) {
            $fixtures[] = Response::fromMessage($item);
        }

        return $fixtures;
    }
}
