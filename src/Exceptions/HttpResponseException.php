<?php
namespace IvanKurcubic\AnanasAPI;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class HttpResponseException extends RuntimeException
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getResponse():ResponseInterface
    {
        return $this->response;
    }
}
