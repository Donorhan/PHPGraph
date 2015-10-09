<?php
namespace PHPGraph;

/**
 * A Consequence to add on a link
 * @package PHPGraph
 */
class Consequence
{
    /**
     * Consequence's key
     * @var string
     */
    private $key = '';

    /**
     * Value to assign
     * @var mixed
     * @default null
     */
    private $value = null;

    /**
     * Custom function
     * @var callable
     */
    private $custom = null;

    /**
     * Owner
     * @var Link
     */
    private $owner = null;

    /**
     * Default constructor
     *
     * @param string $key
     * @param mixed $value
     */
    function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Create a consequence with default mode
     *
     * @param string $key
     * @param mixed $value
     * @return Consequence
     */
    static function create($key, $value)
    {
        return new Consequence($key, $value);
    }

    /**
     * Create a consequence with custom logic
     *
     * @param callable $custom
     * @return Consequence
     */
    static function createCustom($custom)
    {
        $consequence = new Consequence('', null);
        $consequence->setCustomLogic($custom);

        return $consequence;
    }

    /**
     * Create a consequence with a custom logic function
     *
     * @param callable $custom
     * @return Consequence
     */
    public function setCustomLogic($custom)
    {
        $this->custom = $custom;
    }

    /**
     * Set consequence's owner (you should not call this one manually)
     *
     * @param Link $link
     */
    public function setOwner(Link $link)
    {
        $this->owner = $link;
    }

    /**
     * Execute the consequence
     *
     * @param array $data A reference to the array to fill with consequence data
     * @return bool True if everything was done successfully
     */
    function execute(&$data)
    {
        // Custom consequence stuff …
        if ($this->custom) {
            return call_user_func($this->custom, $data);
        }

        // Stock state
        $data[$this->key] = $this->value;

        return false;
    }
}