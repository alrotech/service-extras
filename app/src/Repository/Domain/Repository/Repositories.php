<?php declare(strict_types = 1);
/**
 * Copyright (c) 2016 Alroniks Experts LLC
 *
 * @author: Ivan Klimchuk <ivan@klimchuk.com>
 * @package: AES Repository
 */

namespace Alroniks\Repository\Domain\Repository;

use Alroniks\Repository\AbstractRepository;

/**
 * Class Repositories
 * @package Alroniks\Repository\Domain\Repository
 */
class Repositories extends AbstractRepository 
{
    /**
     * @param int $perPage
     * @return mixed
     */
    public function paginate(int $perPage = 10)
    {
        // TODO: Implement paginate() method.
    }
}
