<p style="color:black;">Hello {{ $application->name }} {{ $application->last_name }},</p>

<p style="color:black;">The status of your JMOR Tech Talk Show guest application has been updated.</p>

<p style="color:black;"><strong>New status: </strong>{{ $statusLabel }}</p>
<p style="color:black;"><strong>Application email: </strong>{{ $application->email }}</p>

@if($statusLabel === 'Accepted')
    <p style="color:black;">Congratulations! Our team will reach out to you with next steps.</p>
@elseif($statusLabel === 'Rejected')
    <p style="color:black;">Thank you for your interest. Unfortunately we are not moving forward with your application at this time.</p>
@endif

<p style="color:black;">— The JMOR Connection, Inc. Team</p>
