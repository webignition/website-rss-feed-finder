Website RSS/ATOM Feed Finder [![Build Status](https://secure.travis-ci.org/webignition/website-rss-feed-finder.png?branch=master)](http://travis-ci.org/webignition/website-rss-feed-finder)
===========================

Overview
---------

Finds the RSS or ATOM feed URL for a given website. That's all.

Usage
-----

### The "Hello World" example

```php
<?php
$finder = new webignition\WebsiteRssFeedFinder\WebsiteRssFeedFinder();        

$finder->setRootUrl('http://codinghorror.com/blog/');
$this->assertEquals('http://feeds.feedburner.com/codinghorror/', $finder->getRssFeedUrl());

$finder->setRootUrl('http://www.geekyportal.com/');        
$this->assertEquals('http://www.geekyportal.com/feeds/posts/default', $finder->getAtomFeedUrl());        
);
```

Building
--------

#### Using as a library in a project

If used as a dependency by another project, update that project's composer.json
and update your dependencies.

    "require": {
        "webignition/website-rss-feed-finder": "*"      
    }

#### Developing

This project has external dependencies managed with [composer][3]. Get and install this first.

    # Make a suitable project directory
    mkdir ~/website-rss-feed-finder && cd ~/website-rss-feed-finder

    # Clone repository
    git clone git@github.com:webignition/website-rss-feed-finder.git.

    # Retrieve/update dependencies
    composer.phar install

Testing
-------

Have look at the [project on travis][4] for the latest build status, or give the tests
a go yourself.

    cd ~/website-rss-feed-finder
    phpunit tests

An instance of `WebsiteRssFeedFinder` can be passed an HTTP client with which
to retrieve the content of the specified sitemap URL.

Examine the existing unit tests to see how you can pass in a mock HTTP client to
enable testing without the need to perform actual HTTP requests.


[3]: http://getcomposer.org
[4]: http://travis-ci.org/webignition/website-rss-feed-finder/builds