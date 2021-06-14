<?php

namespace OknJaviFernandez\Changelog\Adapters;

use DOMDocument;
use Illuminate\Filesystem\Filesystem;
use OknJaviFernandez\Changelog\Change;
use OknJaviFernandez\Changelog\Exceptions\FileNotFoundException;
use OknJaviFernandez\Changelog\Exceptions\InvalidXmlException;
use OknJaviFernandez\Changelog\Feature;

class XmlFeatureAdapter implements FeatureAdapter
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * XmlFeatureAdapter constructor.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }

    /**
     * Load a feature.
     *
     * @param string $path
     * @return Feature
     */
    public function read(string $path): Feature
    {
        try {
            $content = $this->filesystem->get($path);
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            throw new FileNotFoundException($path);
        }

        $element = simplexml_load_string($content);

        $feature = new Feature;

        foreach ($element->children() as $change) {
            $type = $change->attributes()['type'];
            $visibility = array_key_exists('visibility', $change->attributes()) ? $change->attributes()['visibility'] : 'public';
            if (is_null($type)) {
                throw new InvalidXmlException('Missing `type` attribute on change element.');
            }
            $message = (string) $change;

            $feature->add(new Change($type, $message, $visibility));
        }

        return $feature;
    }

    /**
     * Store a feature.
     *
     * @param string $path
     * @param Feature $feature
     */
    public function write(string $path, Feature $feature): void
    {
        $xml = new DOMDocument();
        $xml->formatOutput = true;
        $xml->encoding = 'UTF-8';

        $root = $xml->createElement('feature');

        foreach ($feature->changes() as $change) {
            $element = $xml->createElement('change', $change->message());
            $element->setAttribute('type', $change->type());
            $element->setAttribute('visibility', $change->visibility());
            $root->appendChild($element);
        }

        $xml->appendChild($root);
        $output = $xml->saveXML();

        $directory = dirname($path);
        if ($this->filesystem->isDirectory($directory) === false) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }

        $this->filesystem->put($path, $output);
    }

    /**
     * Check if there is an existing feature on the given path.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return $this->filesystem->exists($path) && $this->filesystem->isReadable($path);
    }
}
