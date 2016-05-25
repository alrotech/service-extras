<?php

namespace Alroniks\Repository\Domain\Repository;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\TransformerInterface;

/**
 * Class Transformer
 * @package Alroniks\Repository\Domain\Repository
 */
class Transformer implements TransformerInterface
{
    /**
     * @param EntityInterface $entity
     * @return array
     */
    public static function transform(EntityInterface $entity) : array
    {
        /** @var Repository $entity */
        $output = $entity->toArray();

        if (isset($output['description'])) {
            $output['description'] = [
                '@cdata' => $entity->getDescription()
            ];
        }

        $output['packages'] = 19;

        return $output;
    }
}
