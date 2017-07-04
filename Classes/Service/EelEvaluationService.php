<?php

namespace Ttree\EelShell\Service;
use Neos\Eel\EelEvaluatorInterface;
use Neos\Eel\Package;
use Neos\Eel\Utility;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
final class EelEvaluationService
{
    /**
     * @var array
     * @Flow\InjectConfiguration(path="defaultContext", package="Neos.Fusion")
     */
    protected $defaultContextConfiguration;

    /**
     * @var EelEvaluatorInterface
     * @Flow\Inject(lazy=false)
     */
    protected $eelEvaluator;

    public function isValidExpression(string $expression)
    {
        return preg_match(Package::EelExpressionRecognizer, $expression);
    }

    public function evaluate(string $expression, array $context)
    {
        return Utility::evaluateEelExpression($expression, $this->eelEvaluator, $context, $this->defaultContextConfiguration);
    }
}
