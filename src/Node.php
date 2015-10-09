<?php
namespace PHPGraph;

/**
 * Node
 * @package PHPGraph
 */
class Node
{
    /**
     * Unique identifier
     * @var string
     */
    private $uid = 0;

    /**
     * Unique identifier
     * @var string
     */
    private $name = '';

    /**
     * Default constructor
     *
     * @param string $name
     */
    function __construct($name = '')
    {
        $this->uid = uniqid();
        $this->name = $name;
    }

    /**
     * Get unique identifier
     *
     * @return string
     */
    function getId()
    {
        return $this->uid;
    }

    /**
     * Get name
     *
     * @return string
     */
    function getName()
    {
        return $this->name;
    }
}