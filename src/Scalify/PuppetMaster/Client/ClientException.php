<?php

namespace Scalify\PuppetMaster\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * ClientException is thrown when an error occurred during request lifecycle.
 *
 * @package Scalify\PuppetMaster\Client
 */
class ClientException extends RuntimeException
{
    /** @var RequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /**
     * ClientException constructor.
     *
     * @param string                 $message
     * @param RequestInterface       $request
     * @param ResponseInterface|null $response
     */
    public function __construct(string $message, RequestInterface $request, ResponseInterface $response = null)
    {
        parent::__construct($message, $response !== null ? $response->getStatusCode() : 0);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
