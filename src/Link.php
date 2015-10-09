<?php
namespace PHPGraph;

/**
 * Link
 * @package PHPGraph
 */
class Link extends ConditionGroup
{
    /**
     * Origin
     * @var Node
     */
    private $origin = null;

    /**
     * Destination
     * @var Node
     */
    private $destination = null;

    /**
     * Consequences to apply when the conditions are validated
     * @var array
     */
    private $consequences = [];

    /**
     * Default constructor
     *
     * @param Node $origin
     * @param Node $destination
     */
    function __construct(Node $origin, Node $destination)
    {
        $this->origin = $origin;
        $this->destination = $destination;
    }

    /**
     * Add a consequence to the link
     *
     * @info shortcut
     * @param string $key
     * @param mixed $value
     * @return Link
     * @see addConditionInstance
     */
    function addConsequence($key, $value)
    {
        return $this->addConsequenceInstance(new Consequence($key, $value));
    }

    /**
     * Add a consequence to the link
     *
     * @param Consequence $consequence
     * @return Link
     */
    function addConsequenceInstance(Consequence $consequence)
    {
        $consequence->setOwner($this);
        $this->consequences[] = $consequence;

        return $this;
    }

    /**
     * Get destination
     *
     * @return Node
     */
    function getDestination()
    {
        return $this->destination;
    }

    /**
     * Apply linked consequences
     *
     * @param array $data Array to fill with consequences data
     * @return bool True if everything was ok, otherwise false
     */
    function applyConsequences(&$data)
    {
        $result = true;
        foreach ($this->consequences as $consequence) {
            $result &= $consequence->execute($data);
        }

        return $result;
    }

    /**
     * Check if the given node is the origin of the link
     *
     * @param Node $node
     * @return bool
     */
    function isOrigin($node)
    {
        return $node == $this->origin;
    }

    /**
     * Check if the given node is the destination of the link
     *
     * @param Node $node
     * @return bool
     */
    function isDestination($node)
    {
        return $node == $this->destination;
    }
}