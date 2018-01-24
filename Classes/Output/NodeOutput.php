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
            'Label' => $node->getLabel(),
            'Identifier' => $node->getIdentifier(),
            'NodeType' => $node->getNodeType(),
            'ContextPath' => $node->getContextPath(),
        ];
    }

    protected function outputObjectList(array $table)
    {
        $position = 0;
        foreach ($table as $row) {
            $this->output->outputResult();
            $this->output->outputResult('%d.', [$position]);
            foreach ($row as $key => $value) {
                $value = $row[$key];
                if (\is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                }
                $this->output->outputResult('  <info>%s</info>: %s', [$key, $value]);
            }
            $position++;
        }
    }
}
