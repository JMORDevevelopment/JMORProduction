<!DOCTYPE html>
<html>
<body style="margin:0; padding:0; background:#ffffff; font-family: Helvetica, Arial, sans-serif;">
<div style="border:5px solid lightseagreen; padding:5px; max-width:600px; margin:0 auto; border-radius:0; box-sizing:border-box;">
    <table style="width:100%; background:#fff;">
        <tr>
            <td style="padding:10px; text-align:left;">
                <a href="{{ route('home') }}" style="color:#000; text-decoration:none; font-size:13px; font-style:italic;">Goto Website</a>
            </td>
        </tr>
    </table>
    <div style="padding:10px 20px; font-size:17px;">
        {!! nl2br(e($body)) !!}
    </div>
    <div style="font-size:12px; margin-top:20px; padding:5px; background:#eee; text-align:center;">
        © {{ date('Y') }} <a href="{{ route('home') }}" style="color:#000;">jmor.com</a>
    </div>
    <div style="text-align:center; font-size:12px; padding:5px;">
        Proudly Designed, Hosted &amp; Maintained by Neighborhood Publications
        <br>
        We Give your Business a Voice™
    </div>
</div>
</body>
</html>
