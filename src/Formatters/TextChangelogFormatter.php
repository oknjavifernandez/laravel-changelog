<?php

namespace OknJaviFernandez\Changelog\Formatters;

use OknJaviFernandez\Changelog\Release;

class TextChangelogFormatter extends ChangelogFormatter
{
    /**
     * Format a single release to a string format.
     *
     * @param Release $release
     * @return string
     */
    public function single(Release $release): string
    {
        $version = ($this->config('capitalize', true))
            ? ucfirst($release->version())
            : $release->version();

        $lines = [$version];
        foreach ($release->changes() as $change) {
            $type = ucfirst($change->type());
            $lines[] = "  - {$type}: {$change->message()}";
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Format a list of releases to a string format.
     *
     * @param Release[]|array $releases
     * @return string
     */
    public function multiple(array $releases): string
    {
        $lines = [];
        foreach ($releases as $release) {
            $lines[] = $this->single($release);
        }

        return implode(PHP_EOL, $lines);
    }
}
