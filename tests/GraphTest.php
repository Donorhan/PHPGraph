<?php
namespace PHPGraph\Test;

/**
 * Class GraphTest
 * @package PHPGraph\Test
 */
class GraphTest extends \PHPUnit_Framework_TestCase
{
    private function buildGraph($user)
    {
        // Init a graph
        $graph = new \PHPGraph\Graph();

        // Create nodes
        $graph->createNode('Gender');
        $graph->createNode('Hall A');
        $graph->createNode('Hall B');
        $graph->createNode('Hall C');

        // Create links.
        $graph->link('Gender', 'Hall A')
            ->addCondition($user->size, '>=', 1.90)
            ->addConsequence('car_color', 'red');

        $graph->link('Gender', 'Hall B')
            ->createConditionGroup('&&')
                ->addCondition($user->size, '>', 1.60)
                ->addCondition($user->size, '<', 1.90 )
            ->close()
            ->addConsequence('car_color', 'orange');

        $graph->link('Gender', 'Hall C')
            ->addCondition($user->size, '<', 1.60)
            ->addConsequence('car_color', 'yellow');

        return $graph;
    }

    public function testBase()
    {
        $user = new \stdClass();
        $user->size = 1.70;

        $graph = $this->buildGraph($user);
        $graph->visit();

        $this->assertEquals($graph->hasGonethrough('Hall B'), true);
    }
}
