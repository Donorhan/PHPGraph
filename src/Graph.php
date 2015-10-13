<?php
namespace PHPGraph;

/**
 * Graph: The controller of the nodes, transitions,….
 * @package PHPGraph
 */
class Graph
{
    /**
     * Collection of Link
     * @var \PHPGraph\Link[]
     */
    private $links = [];

    /**
     * Collection of Node
     * @var \PHPGraph\Node[]
     */
    private $nodes = [];

    /**
     * Path used to traverse the graph
     * @var \PHPGraph\Node[]
     */
    private $path = [];

    /**
     * Start point
     * @var \PHPGraph\Node
     */
    private $root = null;

    /**
     * Create a new Node in the graph
     *
     * @param string $name
     * @return Node
     */
    public function createNode($name)
    {
        $node = new Node($name);
        $this->nodes[] = $node;

        // Set as default node
        if (!$this->root) {
            $this->root = $node;
        }

        return $node;
    }

    /**
     * Link a node to another one
     *
     * @param string $node_a
     * @param string $node_b
     * @return Link
     * @throws Exception
     */
    public function link($node_a, $node_b)
    {
        return $this->rawLink($this->searchNode($node_a, true), $this->searchNode($node_b, true));
    }

    /**
     * Link a node to another one using Node instances
     *
     * @param Node $node_a
     * @param Node $node_b
     * @return Link
     * @throws \Exception
     */
    public function rawLink(Node $node_a, Node $node_b)
    {
        if (!$node_a || !$node_b) {
            throw new \Exception('You can\'t link null nodes');
        }

        if ($node_a == $node_b) {
            throw new \Exception('You can\'t link a node to himself');
        }

        $link = new Link($node_a, $node_b);
        $this->links[] = $link;

        return $link;
    }

    /**
     * Check if the graph gone through the given node during the visit
     *
     * @param string $search
     * @return bool
     * @see visit
     */
    public function hasGonethrough($search)
    {
        $node = $this->searchNode($search);
        if (!$node) {
            return false;
        }

        return in_array($node, $this->path);
    }

    /**
     * Search a node by his name or by his identifier
     *
     * @param string $search
     * @param bool $strict
     * @return Node|null
     * @throws \Exception
     */
    public function searchNode($search, $strict = false)
    {
        $search = strtolower($search);

        $result = null;
        foreach ($this->nodes as $node) {
            if (strtolower($node->getId()) == $search || strtolower($node->getName()) == $search) {
                $result = $node;
                break;
            }
        }

        if ($strict && !$result) {
            throw new \Exception('Unable to found : ' . $search);
        }

        return $result;
    }

    /**
     * Visit the graph
     *
     * @return array
     */
    public function visit()
    {
        $activeNode = $this->root;
        $previousNode = $activeNode;

        // Stock results
        $results = [];
        $results['data'] = [];
        $results['path'] = [];

        // Init path
        $this->path = [];
        $this->path[] = $activeNode;

        // Ensure every consequences keys exists in the result array
        $this->initConsequencesKeys($results['data']);

        while ($activeNode) {

            // Find next node
            foreach ($this->links as $link) {
                if ($link->isOrigin($activeNode)) {
                    if ($link->isValid()) {

                        // Apply consequences
                        $link->applyConsequences($results['data']);

                        // Switch
                        $previousNode = $activeNode;
                        $activeNode = $link->getDestination();

                        // Save it to the path
                        $this->path[] = $activeNode;
                    }
                }
            }

            // Can't go to another node? We are stuck in a loop
            if ($previousNode == $activeNode) {
                $activeNode = null;
            }

            $previousNode = $activeNode;
        }

        $results['path'] = $this->path;
        return $results;
    }

    /**
     * Get all consequences keys from the graph and set them to null
     * Ensure that keys exists to avoid errors
     *
     * @param array $data Array to fill
     */
    private function initConsequencesKeys(&$data)
    {
        foreach ($this->links as $link) {
            $link->applyConsequences($data);
        }

        foreach ($data as $key => $value) {
            $data[$key] = null;
        }
    }
}
