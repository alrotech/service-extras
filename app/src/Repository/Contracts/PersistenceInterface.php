<?php

namespace Alroniks\Repository\Contracts;

interface PersistenceInterface
{
    public function persist($data);
    public function retrieve($key);
}
