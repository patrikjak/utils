<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $appName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif,
            'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            background-color: #EBEEFE;
            margin: 0;
            padding: 0;
            width: 100% !important;
            color: #010722;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        h2 {
            font-size: 18px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        h3 {
            font-size: 16px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        a {
            font-size: 15px;
            color: #010722;
            text-align: left;
            word-break: break-word;
        }

        p {
            text-align: left;
            word-break: break-word;
            line-height: 24px;
            font-size: 15px;
            color: #4F5059;
        }

        .background {
            background-color: #EBEEFE;
            padding: 16px;
            border-radius: 12px;
        }

        .background.center {
            text-align: center;
        }

        .space-top {
            margin-top: 40px;
        }

        button,
        a.button-like {
            display: inline-block;
            padding: .75rem 2.5rem;
            background-color: #4360EE;
            transition: .3s;
            border: none;
            border-radius: 8px;
            color: #FFFFFF;
            cursor: pointer;
            font-family: inherit;
            font-size: .9375rem;
            text-decoration: none;
        }

        .header {
            background-color: #4360EE;
            padding: 40px 120px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .header a {
            text-decoration: none;
            font-weight: bold;
            font-size: 2.5rem;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 1rem;
        }

        .header img {
            max-width: 150px;
            max-height: 48px;
        }

        .content {
            width: 75%;
            background-color: #FFFFFF;
            margin: 25px auto;
            border-radius: 8px;
            text-align: left;
        }

        .body {
            padding: 64px 120px;
        }

        .footer p {
            font-size: 14px;
            padding: 0 120px 60px 120px;
        }

        @media only screen and (max-width: 991.98px) {
            .content {
                width: 90%;
            }

            .header a {
                font-size: 2.25rem;
            }

            .header img {
                max-height: 40px;
            }

            .header, .body {
                padding: 40px 64px;
            }

            .footer p {
                padding: 0 64px 40px;
            }
        }

        @media only screen and (max-width: 575.98px) {
            .space-top {
                margin-top: 24px;
            }

            .header, .body {
                padding: 24px;
            }

            .header a {
                font-size: 2rem;
            }

            .header img {
                max-height: 36px;
            }

            .header img {
                max-width: 100px;
            }

            .footer p {
                padding: 0 24px 24px;
            }
        }
    </style>
</head>
<body>
<div class="content">
    <x-pjutils::email.header :use-logo="$useLogo" />

    <div class="body">
        {{ $slot }}
    </div>

    <x-pjutils::email.footer :footer-text="$footerText" />
</div>
</body>
</html>