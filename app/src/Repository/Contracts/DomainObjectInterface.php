<?php

namespace Alroniks\Repository\Contracts;

/**
 * Interface DomainObjectInterface
 */
interface DomainObjectInterface
{
    public static function ID($uniqueString);

    public function getId();

    public function __toArray();
}
