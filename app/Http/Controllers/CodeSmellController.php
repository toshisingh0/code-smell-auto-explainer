<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CodeSmells\Rules\FatControllerRule;


class CodeSmellController extends Controller
{
    public function analyze(Request $request)
    {
        // Simple validation
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = $request->input('code');

        // Rule call
        $rule = new FatControllerRule();

       if ($rule->detect($code)) {
        return view('code-smell.analyze', [
            'result' => [
                'detected' => true,
                'explanation' => $rule->explain()
            ]
        ]);
        }

        return view('code-smell.analyze', [
            'result' => [
                'detected' => false,
                'message' => 'No Fat Controller detected'
            ]
        ]);

    }
}
