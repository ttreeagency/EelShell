<?php

namespace Ttree\EelShell\Output;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;

class NodeOutput extends AbstractOutput
{
    public static $supportedTypes = [Node::class];

    public static $priority = 1;

    /**
     * @param NodeInterface $value
     */
    public function output($value)
    {
        $this->outputList([
            $this->extractProperties($value)
        ]);
    }

    protected function extractProperties(NodeInterface $node)
    {
        return [
            'Label' => $node->getLabel(),
            'Identifier' => $node->getIdentifier(),
            'NodeType' => $node->getNodeType(),
            'ContextPath' => $node->getContextPath(),
        ];
    }

}
