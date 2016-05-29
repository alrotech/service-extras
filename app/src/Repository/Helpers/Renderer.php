<?php declare(strict_types = 1);

namespace Alroniks\Repository\Helpers;

use LSS\Array2XML;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Body;

/**
 * Class Renderer
 * @package Alroniks\Repository\Helpers
 */
class Renderer
{
    /**
     * Render output in specified format
     * @param ResponseInterface $response
     * @param array $data
     * @param string $contentType
     * @return ResponseInterface
     */
    public function __invoke(ResponseInterface $response, array $data, string $contentType = 'application/xml') : ResponseInterface
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
