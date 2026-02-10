<?php

namespace App\Codesmells\Rules;

class GodMethodRule
{
    private array $data = [];

    private int $lineThreshold = 20;
    private int $complexityThreshold = 3;
    private int $variableThreshold = 3;

    public function detect(string $code): bool
    {
        if (!str_starts_with(trim($code), '<?php')) {
            $code = "<?php\n" . $code;
        }

        $tokens = token_get_all($code);

        $currentMethod = null;
        $braceCount = 0;
        $lineCount = 0;

        foreach ($tokens as $token) {

            if (is_array($token) && $token[0] === T_FUNCTION) {
                $currentMethod = [
                    'name' => 'unknown',
                    'lines' => 0,
                    'complexity' => 1, // base complexity
                    'variables' => []
                ];
                continue;
            }

            if ($currentMethod && is_array($token) && $token[0] === T_STRING && $currentMethod['name'] === 'unknown') {
                $currentMethod['name'] = $token[1];
            }

            if ($currentMethod && $token === '{') {
                $braceCount = 1;
                $lineCount = 1;
                continue;
            }

            if ($braceCount > 0) {

                if (is_array($token) && $token[0] === T_WHITESPACE) {
                    $lineCount += substr_count($token[1], "\n");
                }

                if (is_array($token) && in_array($token[0], [
                    T_IF, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_SWITCH
                ])) {
                    $currentMethod['complexity']++;
                }

                if (is_array($token) && $token[0] === T_VARIABLE) {
                    $currentMethod['variables'][$token[1]] = true;
                }

                if ($token === '{') $braceCount++;
                if ($token === '}') $braceCount--;

                if ($braceCount === 0) {
                    $currentMethod['lines'] = $lineCount;
                    $varCount = count($currentMethod['variables']);

                    if (
                        $lineCount >= $this->lineThreshold ||
                        $currentMethod['complexity'] >= $this->complexityThreshold ||
                        $varCount >= $this->variableThreshold
                    ) {
                        $this->data = [
                            'method' => $currentMethod['name'],
                            'lines' => $lineCount,
                            'complexity' => $currentMethod['complexity'],
                            'variables' => $varCount
                        ];
                        return true;
                    }

                    $currentMethod = null;
                }
            }
        }

        return false;
    }


    public function explain(): array
    {
        return [
            'smell' => 'God Method',
            'severity' => 'High',
            'problem' => 'A method is too large and complex.',
            'why_bad' => 'God methods are hard to understand, test, and maintain.',
            'solution' => [
                'Split method into smaller methods',
                'Move logic to service or helper classes'
            ],
            'metrics' => $this->data
        ];
    }
}
