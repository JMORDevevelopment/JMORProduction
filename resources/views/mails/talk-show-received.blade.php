<p style="color:black;">Hello {{ $application->name }} {{ $application->last_name }},</p>

<p style="color:black;">Thank you for applying to be a guest on the JMOR Tech Talk Show. We have received your application and it is now under review.</p>

<p style="color:black;"><strong>Name: </strong>{{ $application->name }} {{ $application->last_name }}</p>
<p style="color:black;"><strong>Email: </strong>{{ $application->email }}</p>
<p style="color:black;"><strong>Phone: </strong>{{ $application->phone }}</p>
<p style="color:black;"><strong>Signed on: </strong>{{ $application->date_time }}</p>

@if($application->signed_pdf_path)
    <p style="color:black;">A signed copy of your application is attached to this email.</p>
@endif

<p style="color:black;">We will notify you when your application status changes.</p>

<p style="color:black;">— The JMOR Connection, Inc. Team</p>
