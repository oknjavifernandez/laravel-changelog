<?php

namespace OknJaviFernandez\Changelog\Adapters;

use OknJaviFernandez\Changelog\Release;

interface ReleaseAdapter
{
    /**
     * Load a single release.
     *
     * @param string $path
     * @param string $version
     * @param string $visibility
     * 
     * @return Release
     */
    public function read(string $path, string $version, string $visibility): Release;

    /**
     * Load all releases for the given path.
     *
     * @param string $path
     * @param string $visibility
     * 
     * @return Release[]|array
     */
    public function all(string $path, $visibility): array;

    /**
     * Move the unreleased changes to a versioned release.
     *
     * @param string $path
     * @param string $version
     */
    public function release(string $path, string $version): void;

    /**
     * Check if there is an existing release on the given path.
     *
     * @param string $path
     * @param string $version
     * @return bool
     */
    public function exists(string $path, string $version): bool;
}
