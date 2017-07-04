<?php

namespace Ttree\EelShell\Output;

use Neos\Eel\FlowQuery\FlowQuery;

final class FlowQueryOutput extends AbstractOutput
{
    public static $supportedTypes = [FlowQuery::class];
    public static $priority = 1;

    public function output($value): void
    {
        $this->output->outputResult('<info>Your expression return a FlowQuery, use <b>$query</b> to continue</info>');
    }
}
