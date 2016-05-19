<?php

namespace Alroniks\Repository\Controllers;

use alroniks\repository\Renderer;

/**
 * Class Package
 * @package alroniks\repository\controllers
 */
class PackageController
{
    private $renderer;

    /**
     * Package constructor.
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    // methods
}
