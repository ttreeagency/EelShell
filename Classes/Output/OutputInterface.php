<?php

namespace Ttree\EelShell\Output;

use Ttree\EelShell\ConsoleOutput;

interface OutputInterface
{
    public function output($value): void;
}
