<?php declare(strict_types = 1);

namespace App\Responder;

use DOMDocument;
use LSS\Array2XML;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class XmlResponder
{
    private ResponseFactoryInterface $factory;

    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function render(array $data = []): ResponseInterface
    {
        $response = $this->createResponse();

        $response->getBody()->write($this->buildDomTree($data)->saveXML());

        return $response;
    }

    private function buildDomTree(array $data): DomDocument
    {
        $key = count($data) === 1 ? key($data) : 'root';

        return Array2XML::createXML($key, $key !== 'root' ? $data[$key] : $data);
    }

    private function createResponse(): ResponseInterface
    {
        return $this->factory->createResponse()
            ->withHeader('Content-Type', 'text/xml; charset=utf-8');
    }
}
