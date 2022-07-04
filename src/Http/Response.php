<?php
namespace Msg91\Campaign\Http;

class Response
{
    public $headers;
    public $body;
    public $statusCode;

    /**
     * Constructor
     *
     * @public
     * @param {integer} $statusCode
     * @param {string} $body
     * @param {array} $headers
     * @memberof Response
     */
    public function __construct(int $statusCode, ?string $body, ?array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->body = \json_decode($body, true);
        $this->headers = $headers;
    }

    /**
     * Constructor
     *
     * @public
     * @returns string
     * @memberof Response
     */
    public function __toString(): string
    {
        return '[Response] HTTP ' . $this->getStatusCode() . ' ' . $this->body;
    }
}
