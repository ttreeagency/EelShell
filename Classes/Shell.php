<?php

namespace Ttree\EelShell;

use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Flow\Cli;
use Neos\Flow\Exception;
use Neos\Flow\Annotations as Flow;
use Ttree\EelShell\Handler\ContextHandler;
use Ttree\EelShell\Service\EelEvaluationService;
use Ttree\EelShell\Service\OutputResultService;

final class Shell
{
    /**
     * @var ConsoleOutput
     */
    protected $output;

    /**
     * @var string
     */
    protected $prompt;

    /**
     * @var string
     */
    protected $history;

    /**
     * @var ContextHandler
     * @Flow\Inject
     */
    protected $context;

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var EelEvaluationService
     * @Flow\Inject
     */
    protected $eelEvaluationService;

    /**
     * @var OutputResultService
     * @Flow\Inject
     */
    protected $outputService;

    public function loop(Cli\ConsoleOutput $output, array $contextVariables)
    {
        if (!function_exists('readline')) {
            throw new Exception('Readline PHP extension is required for the shell', 1499101458);
        }
        $this->context->pushAll($contextVariables);
        $this->output = new ConsoleOutput($output);
        $this->prompt = 'EEL> ';
        $this->history = \FLOW_PATH_ROOT . '.eel_history';

        $this->output->outputHeader();

        readline_read_history($this->history);

        while (true) {
            $command = trim($this->readline());

            if ($command === '') {
                $this->output->outputResult('Your Oompa Loompa need more works, give them a valid command ...');
                continue;
            }

            readline_add_history($command);
            readline_write_history($this->history);

            $command = trim($command);

            if (\substr($command, 0, 9) === '@context.') {
                $this->pushContextVariable($command);
                continue;
            }

            if (\substr($command, 0, 8) === '@context') {
                $this->output->outputContext($this->context->toArray());
                continue;
            }

            $expression = sprintf("\${%s}", $command);

            if (!$this->eelEvaluationService->isValidExpression($expression)) {
                $this->output->outputResult('<error>Invalid expression:</error> %s', [$expression]);
            } else {
                try {
                    if (strpos($command, '$query.') !== false) {
                        $expression = $this->wrapExpression($this->currentQuery($command));
                    } elseif (\substr($command, 0, 6) === '$query') {
                        $this->output->outputQuery($this->query);
                        continue;
                    }

                    $value = $this->eelEvaluationService->evaluate($expression, $this->context->toArray());

                    $this->output->outputResult('<comment>Expression:</comment> %s', [$expression]);

                    $this->outputService->output($value, $this->output);

                    $this->recordCurrentFlowQuery($value, $command);
                } catch (\Exception $exception) {
                    $this->handleException($exception);
                }
            }
        }
    }

    protected function wrapExpression(string $expression): string
    {
        return sprintf("\${%s}", $expression);
    }

    protected function handleException(\Exception $exception)
    {
        $this->output->outputLine('Oups, something bad happens ...');
        $this->output->outputLine('<error>%s</error>', [$exception->getMessage()]);
    }

    protected function currentQuery(string $command): string
    {
        if ($this->query !== []) {
            return \str_replace('$query', $this->query['command'], $command);
        }
        return $command;
    }

    protected function recordCurrentFlowQuery($value, $command): bool
    {
        if (!$value instanceof FlowQuery) {
            return false;
        }
        $this->query = [
            'command' => $this->currentQuery($command),
            'value' => $value
        ];
        return true;
    }

    protected function pushContextVariable(string $command)
    {
        \preg_match('/@context\.([a-zA-Z0-9]*) *= *(.*)$/', $command, $matches);
        if (!isset($matches[1], $matches[2])) {
            throw new \Neos\Eel\Exception('Invalid assignement expression');
        }
        if ($this->eelEvaluationService->isValidExpression($matches[2])) {
            $value = $this->eelEvaluationService->evaluate($matches[2], $this->context->toArray());
        } else {
            $value = $matches[2];
        }
        $this->context->push($matches[1], $value);

        $this->output->outputLine('// <comment>Variable "%s" pushed in the context: %s</comment>', [$matches[1], $matches[2]]);
    }

    private function readline(): string
    {
        return readline($this->prompt);
    }
}
