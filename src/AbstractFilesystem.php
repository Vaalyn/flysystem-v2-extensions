<?php

namespace Azura\Files;

use Azura\Files\Adapter\ExtendedAdapterInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\PathNormalizer;
use League\Flysystem\StorageAttributes;
use League\Flysystem\UnableToRetrieveMetadata;

abstract class AbstractFilesystem extends Filesystem implements ExtendedFilesystemInterface
{
    protected ExtendedAdapterInterface $adapter;

    public function __construct(
        ExtendedAdapterInterface $adapter,
        array $config = [],
        PathNormalizer $pathNormalizer = null
    ) {
        $this->adapter = $adapter;

        parent::__construct($adapter, $config, $pathNormalizer);
    }

    public function getAdapter(): ExtendedAdapterInterface
    {
        return $this->adapter;
    }

    public function getMetadata(string $path): StorageAttributes
    {
        return $this->adapter->getMetadata($path);
    }

    public function isDir(string $path): bool
    {
        try {
            return $this->getMetadata($path)->isDir();
        } catch (UnableToRetrieveMetadata $e) {
            return false;
        }
    }

    public function isFile(string $path): bool
    {
        try {
            return $this->getMetadata($path)->isFile();
        } catch (UnableToRetrieveMetadata $e) {
            return false;
        }
    }

    public function uploadAndDeleteOriginal(string $localPath, string $to): void
    {
        $this->upload($localPath, $to);
        @unlink($localPath);
    }
}
