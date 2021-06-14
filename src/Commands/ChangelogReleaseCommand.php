<?php

namespace OknJaviFernandez\Changelog\Commands;

use Illuminate\Console\Command;
use OknJaviFernandez\Changelog\Adapters\ReleaseAdapter;
use OknJaviFernandez\Changelog\Exceptions\VersionAlreadyExistsException;

class ChangelogReleaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:release {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move all unreleased changes to a new version';

    /**
     * Execute the command.
     *
     * @param ReleaseAdapter $adapter
     */
    public function handle(ReleaseAdapter $adapter)
    {
        $path = config('changelog.path');
        $version = $this->argument('version');

        try {
            $adapter->release($path, $version);
        } catch (VersionAlreadyExistsException $e) {
            $this->error("Version {$e->getVersion()}");
        }
    }
}
