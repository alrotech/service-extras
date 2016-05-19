<?php

namespace Alroniks\Repository\Controllers;

use Alroniks\Repository\Models\Package\Package;
use Alroniks\Repository\Models\Package\Storage;
use Alroniks\Repository\Models\Package\Transformer;
use alroniks\repository\Renderer;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Package
 * @package alroniks\repository\controllers
 */
class PackageController
{
    /** @var Renderer  */
    private $renderer;

    /** @var Storage */
    private $storage;

    /**
     * Package constructor.
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
        $this->storage = new Storage();

        $this->storage->add(new Package(
            null,
            'modcastsvideo',
            '0.0.0',
            'Ivan Klimchuk',
            'MIT',
            'Packed theme files for website modcasts.video',
            'Packed theme files for website modcasts.video',
            'Packed theme files for website modcasts.video',
            '30 april 2016',
            '18 may 2016',
            '19 may 2016',
            '',
            '',
            '2.4.0',
            '2.6.0',
            'mysql',
            'link to file for download'
        ));

        $this->storage->add(new Package(
            null,
            'videocast',
            '0.0.1',
            'Ivan Klimchuk',
            'MIT',
            'Packed theme files for website modcasts.video',
            'Packed theme files for website modcasts.video',
            'Packed theme files for website modcasts.video',
            '30 april 2016',
            '18 may 2016',
            '20 may 2016',
            '',
            '',
            '2.4.0',
            '2.6.0',
            'mysql',
            'link to file for download'
        ));
    }

    public function search(Request $request, Response $response)
    {
        /*
        'query' => false,
        'tag' => false,
        'sorter' => false,
        'start' => 0,
        'limit' => 10,
        'dateFormat' => '%b %d, %Y',
        'supportsSeparator' => ', ',
         */

        $packages = $this->storage->findAll();

        foreach ($packages as &$package) {
            $package = Transformer::transform($package);
        }

        /** @var Response $response */
        $response = $this->renderer->render($response, [
            'packages' => [
                '@attributes' => [
                    'type' => 'array',
                    'total' => 1,
                    'page' => 1, // todo: need calculate it
                    'of' => 1,
                ],
                'package' => $packages
            ]
        ]);

        return $response->withStatus(200);
    }
}
