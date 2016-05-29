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

        $output['display_name'] = $entity->getSignature();
        $output['release'] = 'pl';

        $output['description'] = ['@cdata' => $entity->getDescription()];
        $output['instructions'] = ['@cdata' => $entity->getInstructions()];
        $output['changelog'] = ['@cdata' => $entity->getChangelog()];

        $output['createdon'] = $entity->getCreatedon()->format(\DateTime::ISO8601);
        $output['editedon'] = $entity->getEditedon()->format(\DateTime::ISO8601);
        $output['releasedon'] = $entity->getReleasedon()->format(\DateTime::ISO8601);

        $output['screenshot'] = $entity->getCover();
        $output['thumbnail'] = $entity->getThumb();

        $output['supports_db'] = $entity->getDatabases();
        $output['minimum_supports'] = $entity->getMinimum();
        $output['breaks_at'] = $entity->getMaximum() ?: 1000000;

        unset($output['storage']);
        unset($output['githublink']);
        unset($output['cover']);
        unset($output['thumb']);
        unset($output['databases']);
        unset($output['minimum']);
        unset($output['maximum']);

        ksort($output);

        return $output;
    }
}
