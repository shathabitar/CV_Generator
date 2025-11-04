@extends('layout')

@php
    $photo_src = null;
    if(!empty($data['photo'])) {
        $photo_path = public_path('storage/' . $data['photo']);
        if(file_exists($photo_path)) {
            if(app()->environment('local')) {
                $photo_src = asset('storage/' . $data['photo']); // Web
            } else {
                $photo_src = 'file://'.$photo_path; // PDF
            }
        }
    }
@endphp

@section('content')
<div style="width: 700px; margin: auto; font-family: Arial, sans-serif; line-height: 1.5;">

    <div style="width: 100%; text-align: center; margin-bottom: 25px;">
        @if($photo_src)
            <div style="width: 120px; height: 120px; margin: 0 auto 10px auto; border-radius: 50%; overflow: hidden; position: relative; border: 2px solid #007BFF;">
                <img src="{{ $photo_src }}" 
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: auto; height: 100%;">
            </div>
        @endif

        <h1 style="margin: 10px 0 0 0; font-size: 30px; font-weight: bold; color: #222;">{{ $data['name'] }}</h1>

        @if(!empty($data['about']))
            <p style="margin-top: 8px; font-size: 14px; color: #555;">{{ $data['about'] }}</p>
        @endif
    </div>

    <!-- Education -->
    @if(!empty($data['education']))
        <h2 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Education</h2>
        @foreach($data['education'] as $edu)
            <p style="margin: 5px 0;">
                <strong>{{ $edu['degree'] }}</strong> — {{ $edu['institution'] }} ({{ $edu['year'] }})
            </p>
        @endforeach
    @endif

    <!-- Experience -->
    @if(!empty($data['experience']))
        <h2 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 15px;">Experience</h2>
        @foreach($data['experience'] as $exp)
            <p style="margin: 5px 0;">
                <strong>{{ $exp['position'] }}</strong> — {{ $exp['company'] }}<br>
                <em>{{ $exp['start_date'] }} @if(!empty($exp['end_date'])) - {{ $exp['end_date'] }} @endif</em><br>
                {{ $exp['description'] ?? '' }}
            </p>
        @endforeach
    @endif

    <!-- Skills -->
    @if(!empty($data['technical_skills']) || !empty($data['soft_skills']))
        <h2 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 15px;">Skills</h2>
        @if(!empty($data['technical_skills']))
            <p style="margin: 5px 0;"><strong>Technical:</strong> {{ implode(', ', $data['technical_skills']) }}</p>
        @endif
        @if(!empty($data['soft_skills']))
            <p style="margin: 5px 0;"><strong>Soft:</strong> {{ implode(', ', $data['soft_skills']) }}</p>
        @endif
    @endif

    <!-- References -->
    @if(!empty($data['reference']))
        <h2 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 15px;">References</h2>
        @foreach($data['reference'] as $ref)
            <p style="margin: 5px 0;">
                <strong>{{ $ref['name'] }}</strong> — {{ $ref['company'] }}<br>
                Phone: {{ $ref['phone_number'] }}<br>
                Email: {{ $ref['email'] }}
            </p>
        @endforeach
    @endif

    <!-- Certificates -->
    @if(!empty($data['certificate']))
        <h2 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 15px;">Certificates</h2>
        @foreach($data['certificate'] as $cert)
            <p style="margin: 5px 0;">
                <strong>{{ $cert['title'] }}</strong> — {{ $cert['company'] }} ({{ $cert['date'] }})
            </p>
        @endforeach
    @endif
</div>

<form id="pdf-download-form" action="{{ route('cv.download.pdf', $userId) }}" method="GET" style="display:none;">
</form>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('pdf-download-form').submit();
    });
</script>
@endsection
