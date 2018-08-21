<?php

namespace Scalify\PuppetMaster\Client;

use Exception;
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
     * @param Exception|null         $previous
     */
    public function __construct(string $message, RequestInterface $request, ResponseInterface $response = null, Exception $previous = null)
    {
        $code = 0;
        if ($response !== null) {
            $code = $response->getStatusCode();
        } else if ($previous !== null) {
            $code = $previous->getCode();
        }

        parent::__construct($message, $code, $previous);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
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
