<?php

namespace Ttree\EelShell\Service;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Reflection\ReflectionService;
use Neos\Utility\PositionalArraySorter;
use Ttree\EelShell\ConsoleOutput;
use Ttree\EelShell\Output\OutputInterface;
use Ttree\EelShell\Output\ScalarOutput;

/**
 * @Flow\Scope("singleton")
 */
final class OutputResultService
{
    /**
     * @var ObjectManagerInterface
     * @Flow\Inject
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $registry;

    public function initializeObject()
    {
        $this->registry = static::registration($this->objectManager);
    }

    public function output($value, ConsoleOutput $output)
    {
        $type = \gettype($value);
        if ($type === 'object') {
            $type = \get_class($value);
        }
        $this->outputType($type, $value, $output);
    }

    protected function hasTypeSupport(string $type): bool
    {
        return isset($this->registry[$type], $this->registry[$type][0]['className']);
    }

    protected function outputType(string $type, $value, ConsoleOutput $output)
    {
        if (!$this->hasTypeSupport($type)) {
            throw new Exception(\vsprintf('Not output handler for type "%s"', [$type]), 1499164647);
        }
        $className = $this->registry[$type][0]['className'];
        /** @var OutputInterface $output */
        $outputHandler = new $className($output);
        $outputHandler->output($value);
    }

    /**
     * @Flow\CompileStatic
     */
    public static function registration(ObjectManagerInterface $objectManager) :array
    {
        /** @var ReflectionService $reflectionService */
        $reflectionService = $objectManager->get(ReflectionService::class);
        $outputClassNames = $reflectionService->getAllImplementationClassNamesForInterface(OutputInterface::class);

        $outputHandlers = array();
        foreach ($outputClassNames as $outputClassName) {
            /** @var array $supportedTypes */
            $supportedTypes = $outputClassName::$supportedTypes;
            foreach ($supportedTypes as $type) {
                if (!isset($outputHandlers[$type]) || !\is_array($outputHandlers[$type])) {
                    $outputHandlers[$type] = [];
                }
                $outputHandlers[$type][] = [
                    'priority' => (integer)$outputClassName::$priority,
                    'className' => $outputClassName
                ];
            }
        }

        foreach ($outputHandlers as $type => $supportedType) {
            $sorter = new PositionalArraySorter($outputHandlers[$type], 'priority');
            $outputHandlers[$type] = array_reverse($sorter->toArray());
        }

        return $outputHandlers;
    }
}
