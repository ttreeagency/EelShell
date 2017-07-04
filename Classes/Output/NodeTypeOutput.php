<?php

namespace Ttree\EelShell\Output;

use Neos\ContentRepository\Domain\Model\NodeType;

final class NodeTypeOutput extends AbstractOutput
{
    public static $supportedTypes = [NodeType::class];
    public static $priority = 1;

    /**
     * @param NodeType $value
     */
    public function output($value): void
    {
        $this->output->outputResult('<info>NodeType:</info> %s', [$value->getName()]);
    }
}
