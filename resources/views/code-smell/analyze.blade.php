<!DOCTYPE html>
<html>
<head>
    <title>Code Smell Auto-Explainer</title>
</head>
<body>

<h2>Code Smell Auto-Explainer</h2>

<form method="POST" action="{{ route('analyze.code') }}">
    @csrf

    <textarea name="code" rows="12" cols="80"
        placeholder="Paste your Controller code here...">{{ old('code') }}</textarea>
    <br><br>

    <button type="submit">Analyze</button>
</form>

<br>

{{-- RESULT SHOW --}}
@if(isset($result))

    @if($result['detected'])

        <h3>❌ {{ $result['explanation']['smell'] }}
            ({{ $result['explanation']['severity'] }})
        </h3>

        <p><strong>Problem:</strong>
            {{ $result['explanation']['problem'] }}
        </p>

        <p><strong>Why bad:</strong>
            {{ $result['explanation']['why_bad'] }}
        </p>

        <p><strong>Solution:</strong></p>
        <ul>
            @foreach($result['explanation']['solution'] as $sol)
                <li>{{ $sol }}</li>
            @endforeach
        </ul>

        @if(!empty($result['explanation']['metrics']))
            <ul>
                @foreach($result['explanation']['metrics'] as $k => $v)
                    <li>{{ ucfirst($k) }} : {{ $v }}</li>
                @endforeach
            </ul>
        @endif

    @else
    <p>✅ {{ $result['message'] ?? 'No code smell detected 🎉' }}</p>
    @endif

@endif


</body>
</html>
