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

    protected function outputList(array $table)
    {
        $position = 0;
        foreach ($table as $row) {
            $this->output->outputResult();
            $this->output->outputResult('%d.', [$position]);
            $padding = $this->longestString(\array_keys($row)) + 1;
            foreach ($row as $key => $value) {
                $value = $row[$key];
                if (\is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                }
                $this->output->outputResult('  <info>%s</info>: %s', [\str_pad($key, $padding), $value]);
            }
            $position++;
        }
    }

    protected function longestString(array $array)
    {
        $mapping = array_combine($array, array_map('strlen', $array));
        return \mb_strlen(array_keys($mapping, max($mapping))[0]);
    }
}
