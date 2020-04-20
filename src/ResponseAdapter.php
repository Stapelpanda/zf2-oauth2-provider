<?php
/**
 * @author Glenn Schmidt <glenn@codeacious.com>
 * @copyright Copyright 2015 Codeacious Pty Ltd
 */

namespace Codeacious\OAuth2Provider;

use OAuth2\Response;
use Laminas\Http\Response as HttpResponse;
use Laminas\Http\Header\GenericHeader;

/**
 * Wraps an OAuth2 library response object to create a ZF response object.
 */
class ResponseAdapter extends HttpResponse
{
    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->setStatusCode($response->getStatusCode());

        $this->getHeaders()
            ->addHeader(new GenericHeader('Content-type', 'application/json'))
            ->addHeaders($response->getHttpHeaders());

        $this->setContent($response->getResponseBody('json'));
    }
}