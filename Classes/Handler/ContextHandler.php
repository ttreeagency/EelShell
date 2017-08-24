<?php

namespace Ttree\EelShell\Handler;

use Neos\Flow\Annotations as Flow;
use Ttree\EelShell\Service\EelEvaluationService;

/**
 * @Flow\Scope("singleton")
 */
final class ContextHandler
{
    protected $context = [];

    /**
     * @var EelEvaluationService
     * @Flow\Inject
     */
    protected $eelEvaluationService;

    public function pushAll(array $context)
    {
        $this->context = \array_merge($this->context, $context);
    }

    public function push(string $name, $value)
    {
        $this->context[$name] = $value;
    }

    public function toArray(): array
    {
        return $this->context;
    }
}
