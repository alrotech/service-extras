<?php

namespace Alroniks\Repository;

use LSS\Array2XML;
use Slim\Http\Body;
use Slim\Http\Response;

/**
 * Class Renderer
 * @package alroniks\repository
 */
class Renderer
{
    protected $request;

    /**
     * Renderer constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Render output in specified format
     * @param Response $response
     * @param array $data
     * @param string $contentType
     * @return static
     */
    public function render(Response $response, array $data, $contentType = 'application/xml')
    {
        switch ($contentType) {
            case 'application/json':
                $output = json_encode($data);
                break;
            case 'application/xml':
            default:
                if (count($data) === 1) {
                    $key = key($data);
                    $xml = Array2XML::createXML($key, $data[$key]);
                } else {
                    $xml = Array2XML::createXML('root', $data);
                }
                $output = $xml->saveXML();
                break;
        }

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);

        return $response
            ->withHeader('Content-type', $contentType)
            ->withBody($body);
    }
}
