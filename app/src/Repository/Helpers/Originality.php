<?php declare(strict_types = 1);

namespace Alroniks\Repository\Helpers;

/**
 * Class Originality
 * @package Alroniks\Repository\Helpers
 */
class Originality
{
    /**
     * @param string $string
     * @return string
     */
    public function __invoke(string $string) : string
    {
        return substr(md5(md5($string)), 0, 10);
    }
}
