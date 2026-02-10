<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CodeSmells\Rules\FatControllerRule;
use App\CodeSmells\Rules\GodMethodRule;




class CodeSmellController extends Controller
{
  public function analyze(Request $request)
    {
        $code = $request->input('code');

        $rules = [
            new FatControllerRule(),
            new GodMethodRule(),
        ];

        foreach ($rules as $rule) {
            if ($rule->detect($code)) {
                return view('code-smell.analyze', [
                    'result' => [
                        'detected' => true,
                        'explanation' => $rule->explain(),
                    ]
                ]);
            }
        }

        return view('code-smell.analyze', [
            'result' => [
                'detected' => false,
                'message' => 'No code smell detected 🎉',
            ]
        ]);
    }



}
