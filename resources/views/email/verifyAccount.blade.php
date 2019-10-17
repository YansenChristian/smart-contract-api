<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title> {{ trans('onboarding.email_signup_title') }} | {{ trans('onboarding.info_app_domain') }} </title>
        <meta name="viewport" content="width=device-width" />
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="bodycontainer">
            <div class="maincontent">
                <table class="message" border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td>
                            <div style="overflow: hidden;">
                                <div style="width: 600px; margin: 0 auto; font-family: 'Noto Sans',sans-serif; font-size: 12px; color: #333333;">
                                    <a href="{{ env('INFO_APP_DOMAIN') }}">
                                        <img style="width: 100%; height: 73px; border-bottom: 1px dashed #ccc;" src="{!! cdn('assets/images/header_ralali_email.jpg') !!}" />
                                    </a>
                                    <div style="text-align: center; margin: 20px 0 25px; font-family: 'Noto Sans';">{{ trans('onboarding.email_dear') }}&nbsp;
                                        <strong class="black" style="color: #000000;">{{ $name }},</strong>
                                        <p>{{ trans('onboarding.email_signup_heading') }}</p>
                                        <p>
                                            <a href="{{ url('v1/auth/verify/'.$confirmation_code) }}" target="_blank" rel="noopener noreferrer"> {{ trans('onboarding.email_signup_link') }} </a>
                                        </p>
                                        <p>{{ trans('onboarding.email_thanks') }}<br/></p>
                                    </div>
                                    <table style="border-top: 1px dashed #ccc; width: 100%; padding: 20px 0;">
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 10px; width: 200px;">
                                                {{ trans('onboarding.info_app_footer') }}
                                            </td>
                                            <td style="text-align: right;">
                                                <a style="display: inline-block;" href="https://www.facebook.com/ralalicom" target="_blank" rel="noopener noreferrer">
                                                    <img src="https://static.ralali.com/assets/images/fb.png" />
                                                </a>
                                                <a style="display: inline-block;" href="https://twitter.com/ralalicom" target="_blank" rel="noopener noreferrer">
                                                    <img src="https://static.ralali.com/assets/images/twitter.png" />
                                                </a>
                                                <a style="display: inline-block;" href="https://plus.google.com/+RalaliCom/about" target="_blank" rel="noopener noreferrer">
                                                    <img src="https://static.ralali.com/assets/images/g+.png" />
                                                </a>
                                                <a style="display: inline-block;" href="https://www.linkedin.com/company/ralali-com" target="_blank" rel="noopener noreferrer">
                                                    <img src="https://static.ralali.com/assets/images/linkedin.png" />
                                                </a>
                                                <a style="display: inline-block;" href="https://www.youtube.com/channel/UCA7tGuG-avOIEzcL97ybZqQ/feed" target="_blank" rel="noopener noreferrer">
                                                    <img src="https://static.ralali.com/assets/images/youtube.png" />
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
