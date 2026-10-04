<!DOCTYPE html>
<html>
<body style="margin:0; padding:0; background:#ffffff; font-family: Helvetica, Arial, sans-serif;">
<div style="border:5px solid #B32018; padding: 5px; max-width:600px; margin:0 auto;">
    <div style="padding:15px;">
        Dear {{ $name ?: 'User' }},
        <br>
        <br>
        Your talk show guest application has been accepted.
        <br>
        <br>
        <a href="{{ $orderUrl }}">Click here to order</a>
    </div>
    <table style="background:#B32018; width:100%;">
        <tbody>
        <tr>
            <td style="text-align:center; padding:8px;">
                <a href="{{ route('home') }}" style="color:#ffffff; text-decoration:none;">www.jmor.com</a>
                &nbsp;|&nbsp;
                <a href="mailto:Info@jmor.com" style="color:#ffffff; text-decoration:none;">Info@jmor.com</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
