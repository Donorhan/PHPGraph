<?php
namespace PHPGraph;

/**
 * A Condition to add on a link
 * @package PHPGraph
 */
class Condition
{
    /**
     * First operand
     * @var mixed
     */
    private $operand_1 = null;

    /**
     * Operator to use
     * @var string
     */
    private $operator = '=';

    /**
     * Second operand
     * @var mixed
     */
    private $operand_2 = array();

    /**
     * Custom function
     * @var callable
     */
    private $custom = null;

    /**
     * Owner
     * @var ConditionGroup
     */
    private $owner = null;

    /**
     * Default constructor
     *
     * @param mixed $operand_1
     * @param string $operator
     * @param mixed $operand_2
     */
    function __construct($operand_1, $operator = '>', $operand_2 = null)
    {
        $this->operand_1 = $operand_1;
        $this->operator = $operator;
        $this->operand_2 = $operand_2;
    }

    /**
     * Create a condition with default mode
     *
     * @param mixed $operand_1
     * @param string $operator
     * @param mixed $operand_2
     * @return Condition
     */
    static function create($operand_1, $operator = '>', $operand_2 = null)
    {
        return new Condition($operand_1, $operator, $operand_2);
    }

    /**
     * Create a condition with custom logic
     *
     * @param callable $custom
     * @return Condition
     */
    static function createCustom($custom)
    {
        $condition = new Condition(null);
        $condition->setCustomLogic($custom);

        return $condition;
    }

    /**
     * Create a condition with a custom logic
     *
     * @param callable $custom
     * @return Condition
     */
    public function setCustomLogic($custom)
    {
        $this->custom = $custom;
    }

    /**
     * Set condition's owner (you should not call this one manually)
     * @param ConditionGroup $group
     */
    public function setOwner(ConditionGroup $group)
    {
        $this->owner = $group;
    }

    /**
     * Check if the condition is valid
     * @return bool True if the link is valid, otherwise false
     */
    function execute()
    {
        // Custom condition …
        if ($this->custom) {
            return call_user_func($this->custom);
        }

        // … or default operators
        switch ($this->operator) {
            case '>':
                return $this->operand_1 > $this->operand_2;
                break;
            case '>=':
                return $this->operand_1 >= $this->operand_2;
                break;
            case '<':
                return $this->operand_1 < $this->operand_2;
                break;
            case '<=':
                return $this->operand_1 <= $this->operand_2;
                break;
            case '!=':
                return $this->operand_1 != $this->operand_2;
                break;
            case '==':
            default:
                return $this->operand_1 == $this->operand_2;
                break;
        }

        return false;
    }
}