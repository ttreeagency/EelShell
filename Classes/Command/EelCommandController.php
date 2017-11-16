<?php
namespace Ttree\EelShell\Command;

use Neos\Eel\EelEvaluatorInterface;
use Neos\Eel\Package;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Neos\Controller\CreateContentContextTrait;
use Psy\Configuration;
use Ttree\EelShell\Shell;

/**
 * @Flow\Scope("singleton")
 */
class EelCommandController extends CommandController
{
    use CreateContentContextTrait;

    /**
     * @Flow\Inject(lazy=false)
     * @var EelEvaluatorInterface
     */
    protected $eelEvaluator;

    /**
     * EEL Shell (REPL)
     *
     * @param string $node Node identifier of the context node
     */
    public function shellCommand(string $node)
    {
        $context = $this->createContentContext('live');
        $node = $context->getNodeByIdentifier($node);
        $context = [
            'node' => $node,
        ];
        (new Shell())->loop($this->output, $context);
    }

    protected function isEelExpression(string $expression)
    {
        return preg_match(Package::EelExpressionRecognizer, $expression);
    }
}
