<?php

namespace App\CodeSmells\Rules;

class FatControllerRule
{
    
   public function detect(string $code, int $threshold = 5): bool
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

        return $methodCount > $threshold;
    }


    public function explain(): array
    {
        return [
            'smell' => 'Fat Controller',
            'severity' => 'High',
            'problem' => 'The controller has grown too large and does too much work.',
            'why_bad' => 'Large controllers are difficult to understand, test, and change without breaking existing features.',
            'solution' => [ 'Keep controllers thin and focused',
                            'Move business logic to Service classes',
                            'Extract database logic into Repository classes',
                            'Use controllers only for request handling and responses'
        ]];
    }

    public function __construct()
    {
        //
    }
}
