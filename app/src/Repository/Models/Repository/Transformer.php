<?php

namespace Alroniks\Repository\Models\Repository;

use DateTime;

class Transformer
{
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
            'packages' => 10, // generic
            'templated' => $repository->getTemplated()
        ];
    }
}
