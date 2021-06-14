<?php

namespace OknJaviFernandez\Changelog\Commands;

use Illuminate\Console\Command;
use OknJaviFernandez\Changelog\Adapters\ReleaseAdapter;
use OknJaviFernandez\Changelog\ChangelogFormatterFactory;
use OknJaviFernandez\Changelog\Release;

class ChangelogUnreleasedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:unreleased';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of unreleased changes';

    /**
     * Execute the command.
     *
     * @param ReleaseAdapter $adapter
     * @param ChangelogFormatterFactory $factory
     */
    public function handle(ReleaseAdapter $adapter, ChangelogFormatterFactory $factory)
    {
        $formatter = $factory->make('text');
        $path = config('changelog.path');

        $release = $this->release($adapter, $path);

        $formatted = $formatter->single($release);
        $this->line($formatted);
    }

    /**
     * Get the unreleased feature.
     *
     * @param ReleaseAdapter $adapter
     * @param string $path
     * @return Release
     */
    private function release(ReleaseAdapter $adapter, string $path): Release
    {
        if ($adapter->exists($path, 'unreleased') === false) {
            return new Release('unreleased');
        }

        return $adapter->read($path, 'unreleased');
    }
}
