<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Package;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\TransformerInterface;

/**
 * Class PackageTransformer
 * @package Alroniks\Repository\Domain\Package
 */
class PackageTransformer implements TransformerInterface
{
    /**
     * @param EntityInterface $entity
     * @return array
     */
    public static function transform(EntityInterface $entity) : array
    {
        /** @var Package $entity */
        $output = $entity->toArray();

        $output['release'] = 'pl';
        $output['description'] = ['@cdata' => $entity->getDescription()];
        $output['instructions'] = ['@cdata' => $entity->getInstructions()];
        $output['changelog'] = ['@cdata' => $entity->getChangelog()];

        $output['createdon'] = $entity->getCreatedon()->format(\DateTime::ISO8601);
        $output['editedon'] = $entity->getEditedon()->format(\DateTime::ISO8601);
        $output['releasedon'] = $entity->getReleasedon()->format(\DateTime::ISO8601);

        $output['breaks_at'] = $output['changelog'] ?: 1000000;

        return $output;
    }
}
