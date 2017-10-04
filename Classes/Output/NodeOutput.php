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
        $this->outputObjectList([
            $this->extractProperties($value)
        ]);
    }

    protected function extractProperties(NodeInterface $node)
    {
        return [
            $node->getLabel(),
            $node->getIdentifier(),
            $node->getNodeType(),
            $node->getContextPath(),
        ];
    }

    protected function outputObjectList(array $table)
    {
        foreach ($table as $row) {
            $this->output->outputResult();
            $this->output->outputResult('<b>%s</b>', [$row[0]]);
            foreach ([
                         'Label',
                         'Identifier',
                         'Type',
                         'Context'
                     ] as $key => $label) {
                $this->output->outputResult('  <info>%s</info>: %s', [$label, $row[$key]]);
            }
        }
    }
}
