<?php

namespace OknJaviFernandez\Changelog\Adapters;

use OknJaviFernandez\Changelog\Change;
use OknJaviFernandez\Changelog\Exceptions\FileNotFoundException;
use OknJaviFernandez\Changelog\Feature;

class FakeFeatureAdapter implements FeatureAdapter
{
    /**
     * @var Change[]|array
     */
    private $changes = [];

    /**
     * @param string $path
     * @param Feature $changes
     */
    public function setChanges(string $path, Feature $changes)
    {
        $this->changes[$path] = $changes;
    }

    /**
     * Load a feature.
     *
     * @param string $path
     * @return Feature
     */
    public function read(string $path): Feature
    {
        if ($this->exists($path) === false) {
            throw new FileNotFoundException($path);
        }

        return $this->changes[$path];
    }

    /**
     * Store a feature.
     *
     * @param string $path
     * @param Feature $feature
     */
    public function write(string $path, Feature $feature): void
    {
        $this->changes[$path] = $feature;
    }

    /**
     * Check if there is an existing feature on the given path.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return array_key_exists($path, $this->changes);
    }
}
