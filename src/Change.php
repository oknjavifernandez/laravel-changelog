<?php

namespace OknJaviFernandez\Changelog;

class Change
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $visibility;

    /**
     * Change constructor.
     *
     * @param string $type
     * @param string $message
     */
    public function __construct(string $type, string $message, string $visibility = 'private')
    {
        $this->type = strtolower($type);
        $this->visibility = strtolower($visibility);
        $this->message = $message;
    }

    /**
     * Get the type of the change.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the message of the change.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * Get the message of the change.
     *
     * @return string
     */
    public function visibility(): string
    {
        return $this->visibility;
    }
}
