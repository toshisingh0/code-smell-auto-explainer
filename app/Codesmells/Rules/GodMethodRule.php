<?php

namespace App\CodeSmells\Rules;
class GodMethodRule
{
    private array $result = [];

    private int $lineThreshold = 80;
    private int $complexityThreshold = 10;
    private int $variableThreshold = 10;

    public function detect(string $code): bool
   {
    $tokens = token_get_all($code);

    $currentMethod = null;
    $braceCount = 0;
    $lineCount = 0;

    for ($i = 0; $i < count($tokens); $i++) {

        // detect function
        if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION) {
            for ($j = $i + 1; $j < count($tokens); $j++) {
                if (is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                    $currentMethod = [
                        'name' => $tokens[$j][1],
                        'lines' => 0,
                        'complexity' => 0,
                        'variables' => []
                    ];
                    break;
                }
            }
        }

        if ($currentMethod && $tokens[$i] === '{') {
            $braceCount = 1;
            $lineCount = 1;
            continue;
        }

        if ($braceCount > 0) {

            if (is_array($tokens[$i]) && $tokens[$i][0] === T_WHITESPACE) {
                $lineCount += substr_count($tokens[$i][1], "\n");
            }

            if (is_array($tokens[$i]) && in_array($tokens[$i][0], [
                T_IF, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_SWITCH
            ])) {
                $currentMethod['complexity']++;
            }

            if (is_array($tokens[$i]) && $tokens[$i][0] === T_VARIABLE) {
                $currentMethod['variables'][$tokens[$i][1]] = true;
            }

            if ($tokens[$i] === '{') $braceCount++;
            if ($tokens[$i] === '}') $braceCount--;

            if ($braceCount === 0) {

                $currentMethod['lines'] = $lineCount;
                $varCount = count($currentMethod['variables']);

                if (
                    $lineCount > 20 ||
                    $currentMethod['complexity'] > 3 ||
                    $varCount > 3
                ) {
                    $this->data = $currentMethod;
                    return true;
                }

                $currentMethod = null;
            }
        }
	    }

	    return false;
	}


    private function analyze(array $methods): bool
    {
        foreach ($methods as $method) {
            if (
                $method['lines'] > $this->lineThreshold ||
                $method['complexity'] > $this->complexityThreshold ||
                $method['variable_count'] > $this->variableThreshold
            ) {
                $this->result = [
                    'detected' => true,
                    'type' => 'god_method',
                    'method' => $method['name'],
                    'metrics' => [
                        'lines' => $method['lines'],
                        'complexity' => $method['complexity'],
                        'variables' => $method['variable_count']
                    ],
                    'explanation' => $this->explain($method)
                ];
                return true;
            }
        }

        return false;
    }

    public function report(): array
    {
        return $this->result ?: ['detected' => false];
    }

	   public function explain(): array
	{
	    return [
	        'smell' => 'God Method',
	        'severity' => 'High',
	        'problem' => 'Method is too large and complex',
	        'why_bad' => 'Hard to read, test and maintain',
	        'solution' => [
	            'Split method into smaller methods',
	            'Move logic to service layer'
	        ],
	        'metrics' => $this->data ?? []
	    ];
	}


}
