<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Package;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\FactoryInterface;

/**
 * Class PackageFactory
 * @package Alroniks\Repository\Domain\Package
 */
class PackageFactory implements FactoryInterface
{
    /**
     * @param array $raw
     * @return EntityInterface
     */
    public function make(array $raw) : EntityInterface
    {
        return new Package(
            (string)($raw['category'] ?? null),
            (string)($raw['id'] ?? null),
            (string)($raw['name'] ?? 'no name'),
            (string)($raw['version'] ?? '0.0.0'),
            (string)($raw['signature'] ?? 'noname-0.0.0-pl'),
            (string)($raw['author'] ?? 'author'),
            (string)($raw['license'] ?? 'license'),
            (string)($raw['description'] ?? 'description'),
            (string)($raw['instructions'] ?? 'instructions'),
            (string)($raw['changelog'] ?? 'changelog'),
            ($raw['createdon'] ?? new \DateTimeImmutable()),
            ($raw['editedon'] ?? new \DateTimeImmutable()),
            ($raw['releasedon'] ?? new \DateTimeImmutable()),
            (string)($raw['cover'] ?? 'cover'),
            (string)($raw['thumb'] ?? 'thumb'),
            (string)($raw['minimum'] ?? '2.2.4'),
            (string)($raw['maximum'] ?? ''),
            (string)($raw['databases'] ?? 'mysql'),
            (integer)($raw['downloads'] ?? 0),
            (string)($raw['storage'] ?? 'storage'),
            (string)($raw['location'] ?? 'location'),
            (string)($raw['githublink'] ?? 'github link')
        );
    }
}
