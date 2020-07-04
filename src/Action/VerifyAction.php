<?php declare(strict_types = 1);

namespace App\Action;

use App\Responder\XmlResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class VerifyAction
{
    private XmlResponder $responder;

    public function __construct(XmlResponder $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke(Request $request, Response $response)
    {
        $data = [
            'status' => [
                'verified' => 1
            ]
        ];

        return $this->responder->render($data);
    }
}
