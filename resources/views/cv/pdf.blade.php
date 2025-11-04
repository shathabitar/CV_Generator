<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data['name'] }} - CV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            width: 700px;
            margin: auto;
        }
        h1, h2 {
            margin: 0 0 10px 0;
        }
        h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-top: 20px;
        }
        p {
            margin: 5px 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <h1>{{ $data['name'] }}</h1>
            @if(!empty($data['about']))
                <p>{{ $data['about'] }}</p>
            @endif
        </div>

        @if(!empty($data['photo']) && file_exists(storage_path('app/public/' . $data['photo'])))
            <img src="{{ storage_path('app/public/' . $data['photo']) }}" width="120" height="120">
        @endif

    </div>

    <!-- Education -->
    @if(!empty($data['education']))
        <h2>Education</h2>
        @foreach($data['education'] as $edu)
            <p><strong>{{ $edu['degree'] }}</strong> — {{ $edu['institution'] }} ({{ $edu['year'] }})</p>
        @endforeach
    @endif

    <!-- Experience -->
    @if(!empty($data['experience']))
        <h2>Experience</h2>
        @foreach($data['experience'] as $exp)
            <p>
                <strong>{{ $exp['position'] }}</strong> — {{ $exp['company'] }}<br>
                <em>{{ $exp['start_date'] }} @if(!empty($exp['end_date'])) - {{ $exp['end_date'] }} @endif</em><br>
                {{ $exp['description'] ?? '' }}
            </p>
        @endforeach
    @endif

    <!-- Skills -->
    @if(!empty($data['technical_skills']) || !empty($data['soft_skills']))
        <h2>Skills</h2>
        @if(!empty($data['technical_skills']))
            <p><strong>Technical:</strong> {{ implode(', ', $data['technical_skills']) }}</p>
        @endif
        @if(!empty($data['soft_skills']))
            <p><strong>Soft:</strong> {{ implode(', ', $data['soft_skills']) }}</p>
        @endif
    @endif

    <!-- References -->
    @if(!empty($data['reference']))
        <h2>References</h2>
        @foreach($data['reference'] as $ref)
            <p>
                <strong>{{ $ref['name'] }}</strong> — {{ $ref['company'] }}<br>
                Phone: {{ $ref['phone_number'] }}<br>
                Email: {{ $ref['email'] }}
            </p>
        @endforeach
    @endif

    <!-- Certificates -->
    @if(!empty($data['certificate']))
        <h2>Certificates</h2>
        @foreach($data['certificate'] as $cert)
            <p>
                <strong>{{ $cert['title'] }}</strong> — {{ $cert['issuer'] ?? $cert['company'] }} ({{ $cert['year'] ?? $cert['date'] }})
            </p>
        @endforeach
    @endif

</body>
</html>
