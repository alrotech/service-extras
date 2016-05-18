<?php

namespace alroniks\repository\controllers;

use alroniks\repository\Renderer;

/**
 * Class Package
 * @package alroniks\repository\controllers
 */
class Package
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
