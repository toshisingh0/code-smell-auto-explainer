<?php

namespace App\CodeSmells\Rules;

class FatControllerRule
{
    
    private int $methodThreshold = 3;
    private array $data = [
        'method_count' => 0,
        'threshold' => 0,
    ];
    public function detect(string $code): bool
    {
        if (!str_starts_with(trim($code), '<?php')) {
            $code = "<?php\n" . $code;
        }

        $tokens = token_get_all($code);
        $methodCount = 0;

        foreach ($tokens as $token) {
            if (is_array($token) && $token[0] === T_FUNCTION) {
                $methodCount++;
            }
        }

        if ($methodCount >= $this->methodThreshold) {
           $this->data = [
            'method_count' => $methodCount,
            'threshold' => $this->methodThreshold,
            ];
                    return $methodCount >= $this->methodThreshold;

        }

        return false;
    }



    public function explain(): array
    {
        return [
            'smell' => 'Fat Controller',
            'severity' => 'High',
            'problem' => 'The controller has grown too large and does too much work.',
            'why_bad' => 'Large controllers are difficult to understand, test, and change without breaking existing features.',
            'solution' => [
                'Keep controllers thin and focused',
                'Move business logic to Service classes',
                'Extract database logic into Repository classes',
                'Use controllers only for request handling and responses'
            ],
            'metrics' => $this->data

        ];
    }


    public function __construct()
    {
        //
    }
}
