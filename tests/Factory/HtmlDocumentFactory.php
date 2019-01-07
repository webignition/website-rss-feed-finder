<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Factory;

class HtmlDocumentFactory
{
    public static function load(string $name): string
    {
        return file_get_contents(__DIR__ . '/../Fixtures/HtmlDocuments/' . $name . '.html');
    }
}
