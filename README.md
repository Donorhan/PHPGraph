# PHPGraph

PHPGraph allow you to create conditional graphs in PHP.

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
            
        // Get data (consequences executed, nodes visited, etc)
        $results = $graph->visit();
        
### Installation

You need Composer installed :
```sh
$ composer require dono-sybrix/php-graph
```
