<?php
namespace PHPGraph;

/**
 * A ConditionGroup to add on a link
 * @package PHPGraph
 */
class ConditionGroup
{
    /**
     * Operand to use
     * @var string
     */
    private $operand = '&&';

    /**
     * Conditions to validate the group
     * @var Condition[]
     */
    private $conditions = array();

    /**
     * Groups of conditions to apply when the conditions are validated
     * @var ConditionGroup[]
     */
    private $groups = [];

    /**
     * Owner use to allow "close" method
     * @var ConditionGroup
     */
    private $owner = null;

    /**
     * Add a condition to execute to validate the link
     *
     * @info Shortcut
     * @param mixed $operand_1
     * @param string $operator
     * @param mixed $operand_2
     * @return ConditionGroup|Link
     * @see addConditionInstance
     */
    function addCondition($operand_1, $operator = '>', $operand_2 = null)
    {
        return $this->addConditionInstance(Condition::create($operand_1, $operator, $operand_2));
    }

    /**
     * Add a condition to validate the link
     *
     * @param Condition $condition
     * @return ConditionGroup|Link
     */
    function addConditionInstance(Condition $condition)
    {
        $condition->setOwner($this);
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * Create a sub-group
     *
     * @param string $operand
     * @return ConditionGroup
     */
    function createConditionGroup($operand)
    {
        $group = new ConditionGroup();
        $group->setOperand($operand);
        $group->setOwner($this);
        $this->groups[] = $group;

        return $group;
    }

    /**
     * Close the group
     *
     * @return ConditionGroup|Link
     */
    function close()
    {
        return $this->owner;
    }

    /**
     * Set operand
     *
     * @param string $operand
     */
    function setOperand($operand)
    {
        $this->operand = $operand;
    }

    /**
     * Set owner
     *
     * @param ConditionGroup $owner
     */
    function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Check if the group is valid
     *
     * @return bool True if the group is valid, otherwise false
     */
    function isValid()
    {
        switch($this->operand)
        {
            case '||':
            {
                $result = false;

                foreach ($this->conditions as $condition) {
                    $result |= $condition->execute();
                }
                foreach ($this->groups as $group) {
                    $result |= $group->isValid();
                }

                return $result;
            }
            default:
            case '&&':
            {
                $result = true;

                foreach ($this->conditions as $condition) {
                    $result &= $condition->execute();
                }
                foreach ($this->groups as $group) {
                    $result &= $group->isValid();
                }

                return $result;
            }
        }

        return false;
    }
}