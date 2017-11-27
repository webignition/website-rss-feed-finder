<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Factory;

class HtmlDocumentFactory
{
    /**
     * @param string $name
     *
     * @return string
     */
    public static function load($name)
    {
        return file_get_contents(__DIR__ . '/../Fixtures/HtmlDocuments/' . $name . '.html');
    }
}
