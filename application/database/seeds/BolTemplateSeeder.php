<?php

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class BolTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $template = array(
            'emailtemplate_name' => 'New Bol',
            'emailtemplate_lang' => 'template_lang_new_bol',
            'emailtemplate_type' => 'client',
            'emailtemplate_category' => 'billing',
            'emailtemplate_subject' => 'New Bol - #{bol_id}',
            'emailtemplate_body' => '<!DOCTYPE html>
            <html>
            
            <head>
            
                <meta charset="utf-8">
                <meta http-equiv="x-ua-compatible" content="ie=edge">
                <title>Email Confirmation</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <style type="text/css">
                    @media screen {
                        @font-face {
                            font-family: "Source Sans Pro";
                            font-style: normal;
                            font-weight: 400;
                            src: local("Source Sans Pro Regular"), local("SourceSansPro-Regular"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format("woff");
                        }
            
                        @font-face {
                            font-family: "Source Sans Pro";
                            font-style: normal;
                            font-weight: 700;
                            src: local("Source Sans Pro Bold"), local("SourceSansPro-Bold"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format("woff");
                        }
                    }
            
                    body,
                    table,
                    td,
                    a {
                        -ms-text-size-adjust: 100%;
                        /* 1 */
                        -webkit-text-size-adjust: 100%;
                        /* 2 */
                    }
            
                    img {
                        -ms-interpolation-mode: bicubic;
                    }
            
                    a[x-apple-data-detectors] {
                        font-family: inherit !important;
                        font-size: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                        color: inherit !important;
                        text-decoration: none !important;
                    }
            
                    div[style*="margin: 16px 0;"] {
                        margin: 0 !important;
                    }
            
                    body {
                        width: 100% !important;
                        height: 100% !important;
                        padding: 0 !important;
                        margin: 0 !important;
                        padding: 24px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                        font-size: 16px;
                        background-color: #f9fafc;
                        color: #60676d;
                    }
            
                    table {
                        border-collapse: collapse !important;
                    }
            
                    a {
                        color: #1a82e2;
                    }
            
                    img {
                        height: auto;
                        line-height: 100%;
                        text-decoration: none;
                        border: 0;
                        outline: none;
                    }
            
                    .table-1 {
                        max-width: 600px;
                    }
            
                    .table-1 td {
                        padding: 36px 24px 40px;
                        text-align: center;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .table-1 h1 {
                        margin: 0;
                        font-size: 32px;
                        font-weight: 600;
                        letter-spacing: -1px;
                        line-height: 48px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .table-2 {
                        max-width: 600px;
                    }
            
                    .table-2 td {
                        padding: 36px 24px 0;
                        border-top: 3px solid #d4dadf;
                        background-color: #ffffff;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .table-2 h1 {
                        margin: 0;
                        font-size: 20px;
                        font-weight: 600;
                        letter-spacing: -1px;
                        line-height: 48px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .table-3 {
                        max-width: 600px;
                    }
            
                    .table-2 td {
            
                        background-color: #ffffff;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .td-1 {
                        padding: 24px;
                        font-size: 16px;
                        line-height: 24px;
                        background-color: #ffffff;
                        text-align: left;
                        padding-bottom: 10px;
                        padding-top: 0px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .table-gray {
                        width: 100%;
                    }
            
                    .table-gray tr {
                        height: 24px;
                    }
            
                    .table-gray .td-1 {
                        background-color: #f1f3f7;
                        width: 30%;
                        border: solid 1px #e7e9ec;
                        padding-top: 5px;
                        padding-bottom: 5px;
                        font-size:16px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .table-gray .td-2 {
                        background-color: #f1f3f7;
                        width: 70%;
                        border: solid 1px #e7e9ec;
                        font-size:16px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .button {
                        display: inline-block;
                        padding: 16px 36px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                        font-size: 16px;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 6px;
                        background-color: #1a82e2;
                        border-radius: 6px;
                    }
            
                    .signature {
                        padding: 24px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                        font-size: 16px;
                        line-height: 24px;
                        border-bottom: 3px solid #d4dadf;
                        background-color: #ffffff;
                    }
            
                    .footer {
                        max-width: 600px;
                    }
            
                    .footer td {
                        padding: 12px 24px;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                        font-size: 14px;
                        line-height: 20px;
                        color: #666;
                    }
            
                    .td-button {
                        padding: 12px;
                        background-color: #ffffff;
                        text-align: center;
                        font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
                    }
            
                    .p-24 {
                        padding: 24px;
                    }
                </style>
            
            </head>
            
            <body>
            <!-- start body -->
            <table border="0" width="100%" cellspacing="0" cellpadding="0"><!-- start hero -->
            <tbody>
            <tr>
            <td align="center">
            <table class="table-1" border="0" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td align="left">
            <h1>New Bol</h1>
            </td>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            <!-- end hero --> <!-- start hero -->
            <tr>
            <td align="center">
            <table class="table-2" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td align="left">
            <h1>Hi {first_name},</h1>
            </td>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            <!-- end hero --> <!-- start copy block -->
            <tr>
            <td align="center">
            <table class="table-3" border="0" width="100%" cellspacing="0" cellpadding="0"><!-- start copy -->
            <tbody>
            <tr>
            <td class="td-1">
            <p>Please find attached your bol.</p>
            </td>
            </tr>
            <tr>
            <td class="td-1">
            <table class="table-gray" cellpadding="5">
            <tbody>
            <tr>
            <td class="td-1"><strong>Bol ID</strong></td>
            <td class="td-2">{bol_id}</td>
            </tr>
            <tr>
            <td class="td-1"><strong>Amount</strong></td>
            <td class="td-2">{bol_amount}</td>
            </tr>
            <tr>
            <td class="td-1"><strong>Due Date</strong></td>
            <td class="td-2">{bol_date_due}</td>
            </tr>
            <tr>
            <td class="td-1"><strong>Project</strong></td>
            <td class="td-2">{project_title}</td>
            </tr>
            </tbody>
            </table>
            <p>You can view your bol and make any payments using the link below.</p>
            <p>Your bol is attached.</p>
            </td>
            </tr>
            <tr>
            <td align="left" bgcolor="#ffffff">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td class="td-button">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td align="center"><a class="button" href="{bol_url}" target="_blank" rel="noopener">View Bol</a></td>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            <tr>
            <td class="signature">
            <p>{email_signature}</p>
            </td>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            <!-- end copy block --> <!-- start footer -->
            <tr>
            <td class="p-24" align="center">
            <table class="footer" border="0" width="100%" cellspacing="0" cellpadding="0"><!-- start permission -->
            <tbody>
            <tr>
            <td align="center">
            <p>{email_footer}</p>
            </td>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            <!-- end footer --></tbody>
            </table>
            <!-- end body -->
            </body>
            
            </html>',
            'emailtemplate_variables' => '{first_name}, {last_name}, {bol_id}, {bol_amount}, {bol_amount_due}, {bol_date_created}, {bol_date_due}, {project_title}, {project_id}, {client_name}, {client_id}, {bol_status}, {bol_url}',
            'emailtemplate_created' => '2019-12-08 17:13:10',
            'emailtemplate_updated' => '2021-01-25 18:32:01',
            'emailtemplate_status' => 'enabled',
            'emailtemplate_language' => 'english',
            'emailtemplate_real_template' => 'yes',
            'emailtemplate_show_enabled' => 'yes',
        );


        $temp=EmailTemplate::where('emailtemplate_name',$template['emailtemplate_name'])->first();
        if(!$temp)
        {
            $permission = new EmailTemplate($template);
            $permission->save();
        }
    }
}