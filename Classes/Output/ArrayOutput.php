<?php

namespace Ttree\EelShell\Output;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Utility\TypeHandling;

final class ArrayOutput extends NodeOutput
{
    public static $supportedTypes = ['array'];
    public static $priority = 1;

    /**
     * @param array $value
     */
    public function output($value)
    {
        if ($value === []) {
            $this->output->outputResult(' <comment>empty array</comment>');
            return;
        }

        $table = $this->prepareObjectList($value);
        $this->outputObjectList($table);
    }

    protected function prepareObjectList(array $value): array
    {
        $table = [];
        foreach ($value as $item) {
            if ($item instanceof NodeInterface) {
                $table[] = $this->extractProperties($item);
            } elseif (\is_scalar($item)) {
                $table[] = [
                    'Type' => TypeHandling::getTypeForValue($item),
                    'Value' => $item
                ];
            } elseif (method_exists($item, '__toString')) {
                $table[] = [
                    $item->getLabel(),
                    '',
                    \get_class($item),
                    '',
                ];
            }
        }
        return $table;
    }
}
