<?php

namespace Ttree\EelShell\Output;

use Ttree\EelShell\ConsoleOutput;

abstract class AbstractOutput implements OutputInterface
{
    /**
     * @var ConsoleOutput
     */
    protected $output;

    public function __construct(ConsoleOutput $output)
    {
        $this->output = $output;
    }
}
