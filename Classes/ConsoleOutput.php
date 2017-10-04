<?php

namespace Ttree\EelShell;

use Neos\Flow\Cli;

final class ConsoleOutput
{
    /**
     * @var Cli\ConsoleOutput
     */
    protected $output;

    public function __construct(Cli\ConsoleOutput $output)
    {
        $this->output = $output;
    }

    public function outputResult(string $result = '', array $arguments = [])
    {
        $this->output->outputLine('// %s', [\vsprintf($result, $arguments)]);
    }

    public function outputLine(string $text = '', array $arguments = [])
    {
        $this->output->outputLine($text, $arguments);
    }

    public function outputFormatted(string $text = '', array $arguments = [], int $leftPadding = 0)
    {
        $this->output->outputFormatted($text, $arguments, $leftPadding);
    }

    public function outputQuery(array $query)
    {
        if (isset($query['command'])) {
            $this->outputResult(' <comment>Current query</comment>: %s', [$query['command']]);
        } else {
            $this->outputResult(' <error>Current query not found</error>');
        }
    }

    public function outputContext(array $context)
    {
        $this->outputResult(' <info>Context</info>');
        foreach ($context as $variableName => $variableValue) {
            $this->outputResult(' <comment>%s</comment>: %s', [$variableName, $variableValue]);
        }
    }

    public function outputHeader()
    {
        $this->output->outputFormatted('Welcome to the <info>EEL</info> shell');
        $this->output->outputFormatted('========================');
        $this->output->outputLine();
        $this->output->outputFormatted('Type any EEL expression as if it was enclosed by ${...}.');
        $this->output->outputFormatted('To exit the shell, type <comment>^C</comment>.');
        $this->output->outputLine();
    }
}
