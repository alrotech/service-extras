<?php

namespace Alroniks\Repository\Models\Repository;

use DateTime;

/**
 * Class Transformer
 * @package Alroniks\Repository\Models\Repository
 */
class Transformer
{
    /**
     * @param Repository $repository
     * @return array
     */
    public static function transform(Repository $repository)
    {
        return [
            'id' => $repository->getId(),
            'name' => $repository->getName(),
            'description' => [
                '@cdata' => $repository->getDescription()
            ],
            'createdon' => $repository->getCreatedOn()->format(DateTime::ISO8601),
            'rank' => $repository->getRank(),
            'packages' => 10, // generic todo count packages in repository?
            'templated' => $repository->getTemplated()
        ];
    }
}
