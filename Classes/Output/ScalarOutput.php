<?php

namespace Ttree\EelShell\Output;

final class ScalarOutput extends AbstractOutput
{
    public static $supportedTypes = ['boolean', 'integer', 'double', 'string', 'NULL'];
    public static $priority = 1;

    public function output($value)
    {
        $this->output->outputResult('<info>%s</info>', [$value]);
    }
}
