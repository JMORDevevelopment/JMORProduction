<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>JMOR Talk Show Guest Application</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-top: 22px; margin-bottom: 6px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .meta { color: #555; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        td { padding: 4px 6px; vertical-align: top; border-bottom: 1px solid #eee; }
        td.label { width: 32%; font-weight: bold; color: #333; }
        .signature-box { border: 1px solid #999; padding: 12px; margin-top: 8px; }
        .signature-name { font-size: 20px; font-style: italic; margin-top: 4px; }
        .footer { margin-top: 28px; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <h1>JMOR Tech Talk Show — Guest Application</h1>
    <p class="meta">Signed copy &middot; {{ $application->date_time }}</p>

    <h2>Applicant</h2>
    <table>
        <tr><td class="label">Name</td><td>{{ $application->name }} {{ $application->last_name }}</td></tr>
        <tr><td class="label">Company</td><td>{{ $application->company_name ?: '—' }}</td></tr>
        <tr><td class="label">Email</td><td>{{ $application->email }}</td></tr>
        <tr><td class="label">Phone</td><td>{{ $application->phone }}</td></tr>
        <tr><td class="label">IP address</td><td>{{ $application->ip }}</td></tr>
    </table>

    <h2>Biography</h2>
    <p>{{ $application->bio }}</p>

    <h2>Show Appearances / Past Experience</h2>
    <table>
        @php
            $workNames = array_values(array_filter(explode(',', (string) $application->work_name)));
            $workDetails = array_values(array_filter(explode(',', (string) $application->work_detail)));
            $workLinks = array_values(array_filter(explode(',', (string) $application->weblink)));
        @endphp
        @foreach ($workNames as $index => $work)
            <tr>
                <td class="label">{{ $work }}</td>
                <td>
                    {{ $workDetails[$index] ?? '' }}
                    @if (! empty($workLinks[$index]))
                        <br>{{ $workLinks[$index] }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    <h2>Interview Pitch Concept</h2>
    <p>{{ $application->interview }}</p>

    <h2>Electronic Signature</h2>
    <p>By signing below, the applicant confirms the information provided is accurate and agrees to be considered as a JMOR Tech Talk Show guest.</p>
    <div class="signature-box">
        <div>Signed by:</div>
        <div class="signature-name">{{ $application->signature }}</div>
        <div>Date: {{ $application->date_time }}</div>
    </div>

    <p class="footer">The JMOR Connection, Inc. &middot; Generated {{ now()->format('m/d/Y h:i:s a') }}</p>
</body>
</html>
