<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

                    <!-- Header -->
                    <tr>
                        <td style="background: #FFFFFF; padding: 32px 24px; text-align: center;">
                            <h1
                                style="margin: 0; color: #FF7A1A; font-size: 32px; font-weight: 900; letter-spacing: 1px;">
                                SWAPP</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 32px;">

                            <!-- Greeting -->
                            <h2
                                style="margin: 0 0 16px; color: #000000; font-size: 28px; font-weight: 700; text-align: center;">
                                مرحباً {{ $client->name }}
                            </h2>

                            <p
                                style="margin: 0 0 40px; color: #333333; font-size: 20px; line-height: 1.5; text-align: center; font-weight: 600;">
                                رمز التحقق الخاص بك
                            </p>

                            <!-- Code Boxes -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 32px;">
                                <tr>
                                    <td align="center" style="text-align: center;">
                                        <table cellpadding="0" cellspacing="0" style="display: inline-table;">
                                            <tr>
                                                @foreach (str_split($code) as $digit)
                                                    <td style="padding: 0 6px;">
                                                        <table cellpadding="0" cellspacing="0"
                                                            style="width: 72px; height: 88px; background: #FFFFFF; border: 4px solid #FF7A1A; border-radius: 12px; box-shadow: 0 2px 8px rgba(255,122,26,0.15);">
                                                            <tr>
                                                                <td align="center" valign="middle"
                                                                    style="text-align: center; vertical-align: middle;">
                                                                    <span
                                                                        style="font-size: 56px; font-weight: 900; color: #FF7A1A; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; line-height: 1;">{{ $digit }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #F8F9FA; padding: 24px 32px; text-align: center; border-top: 1px solid #E9ECEF;">
                            <p style="margin: 0 0 12px; color: #333333; font-size: 16px; font-weight: 600;">
                                شكراً لاختيارك SWAPP
                            </p>
                            <p style="margin: 0 0 16px; color: #666666; font-size: 14px;">
                                © 2025 SWAPP. جميع الحقوق محفوظة
                            </p>

                            <!-- Footer Links -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="#"
                                            style="color: #FF7A1A; text-decoration: none; font-size: 14px; font-weight: 600; margin: 0 12px;">الرئيسية</a>
                                        <span style="color: #999;">|</span>
                                        <a href="#"
                                            style="color: #FF7A1A; text-decoration: none; font-size: 14px; font-weight: 600; margin: 0 12px;">تواصل
                                            معنا</a>
                                        <span style="color: #999;">|</span>
                                        <a href="#"
                                            style="color: #FF7A1A; text-decoration: none; font-size: 14px; font-weight: 600; margin: 0 12px;">من
                                            نحن</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
