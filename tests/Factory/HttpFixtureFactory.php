<?php

namespace webignition\Tests\WebsiteRssFeedFinder\Factory;

use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\Response as GuzzleResponse;

class HttpFixtureFactory
{
    /**
     * @param int $statusCode
     * @param string $contentType
     * @param string $body
     *
     * @return GuzzleResponse
     */
    public static function createResponse(
        $statusCode,
        $contentType = null,
        $body = ''
    ) {
        $headers = [];

        if (!empty($contentType)) {
            $headers = [
                'content-type' => $contentType,
            ];
        }

        $messageFactory = new MessageFactory();

        return $messageFactory->createResponse($statusCode, $headers, $body);
    }

    /**
     * @return GuzzleResponse
     */
    public static function createNotFoundResponse()
    {
        return static::createResponse(404);
    }

    /**
     * @param string $contentType
     * @param string $body
     *
     * @return GuzzleResponse
     */
    public static function createSuccessResponse($contentType = null, $body = '')
    {
        return static::createResponse(200, $contentType, $body);
    }
}
