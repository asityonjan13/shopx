<!doctype html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Modern Email Template</title>
    <style media="all" type="text/css">
    /* Global Resets */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      font-size: 16px;
      line-height: 1.6;
      color: #1a1a1a;
      background-color: #ffffff;
    }

    table {
      border-collapse: separate;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
      width: 100%;
    }

    table td {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      font-size: 16px;
      vertical-align: top;
    }

    /* Container */
    .body {
      background-color: #ffffff;
      width: 100%;
    }

    .container {
      margin: 0 auto;
      max-width: 600px;
      padding: 48px 24px;
      width: 100%;
    }

    .content {
      display: block;
      margin: 0 auto;
      max-width: 600px;
    }

    /* Main Content */
    .main {
      background: #ffffff;
      width: 100%;
    }

    .wrapper {
      padding: 0;
    }

    /* Header */
    .header {
      padding-bottom: 32px;
      border-bottom: 1px solid #e5e7eb;
      margin-bottom: 32px;
    }

    .logo {
      font-size: 24px;
      font-weight: 700;
      color: #1a1a1a;
      text-decoration: none;
      letter-spacing: -0.5px;
    }

    /* Typography */
    h1 {
      font-size: 28px;
      font-weight: 600;
      line-height: 1.3;
      color: #1a1a1a;
      margin-bottom: 16px;
      letter-spacing: -0.5px;
    }

    p {
      font-size: 16px;
      font-weight: 400;
      line-height: 1.6;
      color: #4b5563;
      margin-bottom: 16px;
    }

    a {
      color: #2563eb;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    /* Button */
    .btn {
      margin: 32px 0;
    }

    .btn a {
      display: inline-block;
      background-color: #1a1a1a;
      color: #ffffff;
      font-size: 15px;
      font-weight: 500;
      text-decoration: none;
      padding: 14px 32px;
      border-radius: 8px;
      transition: background-color 0.2s;
    }

    .btn a:hover {
      background-color: #2563eb;
      text-decoration: none;
    }

    /* Footer */
    .footer {
      margin-top: 48px;
      padding-top: 32px;
      border-top: 1px solid #e5e7eb;
    }

    .footer p {
      font-size: 14px;
      color: #9ca3af;
      margin-bottom: 8px;
      line-height: 1.5;
    }

    .footer a {
      color: #9ca3af;
      text-decoration: underline;
    }

    .footer a:hover {
      color: #6b7280;
    }

    .divider {
      height: 1px;
      background-color: #e5e7eb;
      margin: 32px 0;
    }

    /* Responsive */
    @media only screen and (max-width: 640px) {
      .container {
        padding: 24px 16px;
      }

      h1 {
        font-size: 24px;
      }

      .btn a {
        display: block;
        text-align: center;
      }
    }
    </style>
  </head>
  <body>
    <table role="presentation" class="body">
      <tr>
        <td class="container">
          <div class="content">
            <table role="presentation" class="main">
              <tr>
                <td class="wrapper">

                  <!-- Header -->
                  <div class="header">
                    <a href="index.html"><img src="{{ asset('assets/frontend/imgs/theme/logo.png') }}" alt="logo" /></a>
                  </div>

                  <!-- Main Content -->
                  <h1>{{ $subject }}</h1>

                  <p>{{ $body }}</p>

                  <p>If you have any questions or need assistance, feel free to reply to this email. We're here to help.</p>
                  <p>Best regards,<br>Ecommerce Shopping Team</p>

                  <!-- Footer -->
                  <div class="footer">
                    <p><strong>Your Company Name</strong><br>
                    123 Business Street, Suite 100<br>
                    City, State 12345</p>

                    <p>You're receiving this email because you signed up for our service.<br>
                    <a href="#">Unsubscribe</a> | <a href="#">Preferences</a> | <a href="#">View in browser</a></p>
                  </div>

                </td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </body>
</html>
