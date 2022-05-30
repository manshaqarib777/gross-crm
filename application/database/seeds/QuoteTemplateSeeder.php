<?php

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class QuoteTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $template = array(
            'emailtemplate_name' => 'New Quote',
            'emailtemplate_lang' => 'template_lang_new_quote',
            'emailtemplate_type' => 'client',
            'emailtemplate_category' => 'billing',
            'emailtemplate_subject' => 'Rate Quote for {user_name}',
            'emailtemplate_body' => '<!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta name="csrf-token" content="xfFqWNOPGR2gKowyrMqDxr6AyJkD9ZzGF4dcOyYb" id="meta-csrf" />
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
            
                    <title>Rate Confirmation for {settings_company_name}</title>
            
            
                    <!-- Favicon icon -->
                    <link rel="icon" type="image/png" sizes="16x16" href="https://redb1rd.com/public/images/favicon.png">
                    <style>
                        @media (max-width: 786px) {
                            #cardTR {
                                display: grid;
                            }
                            #cardTR > td {
                                width: 100% !important;
                                margin-bottom: 10px;
                            }
                        }
                    </style>
                </head>
            
                <body class="pdf-page" style="font-weight: normal;    background: #fff;margin: 0;overflow-x: hidden;color: #67757c;font-weight: 300;font-size: 14px;">
            
                    <div class="bill-pdf  p">
            
                        <!--HEADER-->
                        <div class="bill-header" style="margin-bottom: 15px;">
                            <!--INVOICE HEADER-->
            
                            <!--QUOTE HEADER-->
            
            
            
                            <!--BOL HEADER-->
                            <table style="width: 100%;border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td class="x-left" style="width: 50%;text-align: left;vertical-align: top;">
                                            <div class="x-logo">
                                                <img src="{logo_url}">
                                            </div>
                                        </td>
                                        <td class="x-right" style="width: 50%;text-align: right;">
                                            <div class="x-bill-type">
                                                <h4 style="font-weight: 400;line-height: 22px;font-size: 18px;    margin-bottom: 0.5rem;    margin-top: 0;color: #455a64;">
                                                    <strong style="font-weight: bolder;">{quote_name} #{quote_id}</strong></h4>
                                                <p style="font-weight: 200;line-height: 22px;font-size: 18px;    margin-bottom: 0.5rem;    margin-top: 0;color: #455a64;">
                                                    <strong style="font-weight: bolder;">{quote_date} :{quote_date_name}</strong></p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
            
            
                            <!--ESTIMATE HEADER-->
                        </div>
            
                        <!--ADDRESSES & DATES-->
                        <div class="bill-addresses">
                            <table style="width: 100%;border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <!--company-->
                                        <td class="x-left" style="width: 50%;text-align: left;vertical-align: top;">
                                            <div class="x-company-name">
                                                <h5 class="p-b-0 m-b-0" style="text-align: left;margin-top: 0;color: #455a64;line-height: 18px;
                                                font-size: 16px;
                                                font-weight: normal;margin-bottom: 0px !important;"><strong>{settings_company_name}</strong></h5>
                                            </div>
                                            <div class="x-line" style="text-align: left;
                                            font-weight: normal;line-height: 1.5;">{settings_company_address_line_1}
                                            </div>
                                            <div class="x-line" style="text-align: left;
                                            font-weight: normal;line-height: 1.5;">{settings_company_state}
                                            </div>
                                            <div class="x-line" style="text-align: left;
                                            font-weight: normal;line-height: 1.5;">{settings_company_city}
                                            </div>
                                            <div class="x-line" style="text-align: left;
                                            font-weight: normal;line-height: 1.5;">{settings_company_zipcode}
                                            </div>
                                            <div class="x-line" style="text-align: left;
                                            font-weight: normal;line-height: 1.5;">{settings_company_country}
                                            </div>
            
                                            <!--custom company fields-->
                                        </td>
                                        <td></td>
                                        <!--customer-->
                                        <td class="x-right" style="width: 50%;text-align: right;">
                                            <div class="x-company-name">
                                                <h5 class="p-b-0 m-b-0" style="text-align: right;margin-top: 0;color: #455a64;line-height: 18px;
                                                font-size: 16px;
                                                font-weight: normal;margin-bottom: 0px !important;"><strong>{client_company_name}</strong></h5>
                                            </div>
                                            <div class="x-line" style="text-align: right;
                                            font-weight: normal;line-height: 1.5;">{client_billing_street}
                                            </div>
                                            <div class="x-line" style="text-align: right;
                                            font-weight: normal;line-height: 1.5;">{client_billing_city}
                                            </div>
                                            <div class="x-line" style="text-align: right;
                                            font-weight: normal;line-height: 1.5;">{client_billing_state}
                                            </div>
                                            <div class="x-line" style="text-align: right;
                                            font-weight: normal;line-height: 1.5;">{client_billing_zip}
                                            </div>
                                            <div class="x-line" style="text-align: right;
                                            font-weight: normal;line-height: 1.5;">{client_billing_country}
                                            </div>
                                        </td>
            
                                    </tr>
                                </tbody>
                            </table>
                            <table style="width: 100%;border-collapse: collapse;">
                                <tbody>
                                    <tr id="cardTR">
                                        <!--company-->
                                        <td class="x-left" style="width: 45%;text-align: left;vertical-align: top;">
                                            <!--locations-->
                                            <div class="pull-right bol-locations">
                                                <div class="card" style="position: relative;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: 0.25rem;font-weight: normal;">
                                                    <div class="card-header text-center"
                                                        style=" line-height: 1.5;  word-wrap: break-word; text-align: center!important;padding: 0.75rem 1.25rem;margin-bottom: 0;background-color: rgba(0,0,0,.03);border-bottom: 1px solid rgba(0,0,0,.125); border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;">
                                                        {pickup_location}
                                                    </div>
                                                    <div class="card-body" style="flex: 1 1 auto;min-height: 1px;padding: 1.25rem;">
                                                        <table style="width: 100%;border-collapse: collapse;">
                                                            <tr>
                                                                <td class="x-location-lang font-weight-bold"
                                                                    style="line-height: 1.5;font-weight: 700!important;">{pickup_location}: </td>
                                                                <td class="x-location"> <span>{pickup_location_name}</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="x-delivery-location-lang font-weight-bold"
                                                                    style="line-height: 1.5;font-weight: 700!important;">{pickup_phone}:
                                                                </td>
                                                                <td class="x-delivery-location"> <span>{pickup_phone_name}</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="x-delivery-location-lang font-weight-bold"
                                                                    style="line-height: 1.5;font-weight: 700!important;">{pickup_email}:
                                                                </td>
                                                                <td class="x-delivery-location"> <span>{pickup_email_name}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
            
                                        </td>
                                        <td class="x-left" style="width: 5%;"></td>
                                        <td class="x-right" style="width: 45%;text-align: right;">
                                            <!--locations-->
                                            <div class="pull-right bol-locations">
                                                <div class="card" style="position: relative;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: 0.25rem;font-weight: normal;">
                                                    <div class="card-header text-center"
                                                        style=" line-height: 1.5;  word-wrap: break-word; text-align: center!important;padding: 0.75rem 1.25rem;margin-bottom: 0;background-color: rgba(0,0,0,.03);border-bottom: 1px solid rgba(0,0,0,.125); border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;">
                                                        {delivery_location}
                                                    </div>
                                                    <div class="card-body" style="flex: 1 1 auto;min-height: 1px;padding: 1.25rem;">
                                                        <table style="width: 100%;border-collapse: collapse;">
                                                            <tr>
                                                                <td class="x-location-lang font-weight-bold"
                                                                    style="line-height: 1.5;font-weight: 700!important;">{delivery_location}: </td>
                                                                <td class="x-location"> <span>{delivery_location_name}</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="x-delivery-location-lang font-weight-bold"
                                                                    style="line-height: 1.5;font-weight: 700!important;">{delivery_phone}:
                                                                </td>
                                                                <td class="x-delivery-location"> <span>{delivery_phone_name}</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="x-delivery-location-lang font-weight-bold"
                                                                    style="line-height: 1.5;font-weight: 700!important;">{delivery_email}:
                                                                </td>
                                                                <td class="x-delivery-location"> <span>{delivery_email_name}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="bill-dates" style="width: 100%;border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td class="x-left" style="width: 50%;text-align: left;vertical-align: top;">
                                            <!--dates-->
                                            <div class="pull-left bol-dates">
                                                <table style="width: 100%;border-collapse: collapse;">
                                                    <tr>
                                                        <td class="x-date-lang" id="fx-bol-date-lang" style="height: 25px !important;
                                                        width: 150px;
                                                        font-weight: bold;
                                                        text-transform: capitalize;">{contact_person}: </td>
                                                        <td class="x-date" style=""> <span>{contact_person_name}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="x-date-lang" id="fx-bol-date-lang" style="height: 25px !important;
                                                        width: 150px;
                                                        font-weight: bold;
                                                        text-transform: capitalize;">{contact_details}: </td>
                                                        <td class="x-date" style=""> <span>{contact_details_name}</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td class="x-right" style="width: 50%;text-align: right;">
                                            <!--balances-->
                                            <div class="pull-left bol-dates">
                                                <table style="width: 100%;border-collapse: collapse;">
                                                    <tr>
                                                        <td class="x-date-lang" id="fx-bol-date-lang" style="    text-align: right;height: 25px !important;padding-right: 20px;text-transform: capitalize;font-weight: bold;
                                                        text-transform: capitalize;">{cargo_commodity}: </td>
                                                        <td class="x-date" style=""> <span>{cargo_commodity_name}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="x-date-lang" id="fx-bol-date-lang" style="    text-align: right;height: 25px !important;padding-right: 20px;text-transform: capitalize;font-weight: bold;
                                                        text-transform: capitalize;">{cargo_weight}:</td>
                                                        <td class="x-date" style=""> <span>{cargo_weight_name}</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            
            
                        <!--DATES & AMOUNT DUE-->
            
            
            
            
                        <!--INVOICE TABLE-->
                        <div class="bill-table-pdf" style="font-weight: normal;">
                            <div class="table-responsive m-t-40 invoice-table-wrapper  clear-both" style="    width: 100%;
                            margin-bottom: 1rem;
                            color: #212529;margin-top: 40px !important;">
                                <table class="table table-hover invoice-table " style="border-collapse: collapse;">
                                    <thead>
                                        <tr>
                                            <!--action-->
                                            <!--description-->
                                            <th class="text-left x-description bill_col_description" style="text-align: left!important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;border-bottom: 3px solid #d0d7de;background-color: #f8fafb;color: #212529;">Description</th>
                                            <!--quantity-->
                                            <th class="text-left x-quantity bill_col_quantity" style="text-align: left!important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;border-bottom: 3px solid #d0d7de;background-color: #f8fafb;color: #212529;">Qty</th>
                                            <!--unit price-->
                                            <th class="text-left x-unit bill_col_unit" style="text-align: left!important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;border-bottom: 3px solid #d0d7de;background-color: #f8fafb;color: #212529;">Unit</th>
                                            <!--rate-->
                                            <th class="text-left x-rate bill_col_rate" style="text-align: left!important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;border-bottom: 3px solid #d0d7de;background-color: #f8fafb;color: #212529;">Rate</th>
                                            <!--total-->
                                            <th class="text-right x-total bill_col_total" id="bill_col_total" style="text-align: right !important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;border-bottom: 3px solid #d0d7de;background-color: #f8fafb;color: #212529;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="billing-items-container">
                                        {product_items}
                                        <!-- <tr>
                                            <td class="x-description text-wrap-new-lines" style="text-align: left!important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;color: #212529;">askjdh</td>
                                            <td class="x-quantity" style="text-align: left!important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;color: #212529;">2423</td>
                                            <td class="x-unit" style="text-align: left!important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;color: #212529;">232</td>
                                            <td class="x-rate" style="text-align: left!important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;color: #212529;">1,212.00</td>
                                            <td class="x-total text-right" style="text-align: right !important;vertical-align: bottom;padding: 0.75rem;border-top: 1px solid #dee2e6;width: 270px;color: #212529;">2,936,676.00</td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
            
                        <!-- TOTAL & SUMMARY -->
                        <div class="bill-totals-table-pdf" style="margin-top: 30px !important;    text-align: right!important;font-weight: normal;">
                            <div class="pull-right m-t-30 text-right">
            
                                <table class="invoice-total-table" style="    width: 100%;
                                font-size: 14px;border-collapse: collapse;">
            
                                    <!--adjustment & invoice total-->
                                    <tbody id="invoice-table-section-total" style="color: #ff5c6c !important;
                                    font-size: 18px;
                                    font-weight: bold;">
                                        
                                        <tr class="text-themecontrast" id="billing-sums-total-container">
                                            <td class="billing-sums-total">{bill_final_amount}: </td>
                                            <td id="billing-sums-total" style="text-align: left;padding-left:10px">
                                                <span>{bill_final_amount_name}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
            
                        <!--TERMS-->
                        <div class="invoice-pdf-terms" style="margin-top: 30px;border-top: solid 1px #e9ecef;padding-top: 10px;font-weight: normal;">
                            <h6 style="line-height: 16px;font-size: 14px;color: #455a64;margin-bottom: 0.5rem;margin-top: 0;"><strong>{bill_terms}</strong></h6>
                            {bill_terms_name}
                        </div>
                    </div>
                </body>
            
            </html>',
            'emailtemplate_variables' => '{user_name}, {user_phone}, {user_email}, {user_fax}, {contact_mc_dot_number}, {contact_name}, {contact_phone}, {contact_term}, {contact_fax}, {contact_address}, {contact_dispatcher}, {contact_driver}, {contact_truck}, {contact_trailer}, {product_items}, {quote_amount}, {load_mode}, {load_trailer_type}, {load_trailer_size}, {load_linear_feet}, {load_temperature}, {load_pallet_case_count}, {load_hazmat}, {load_requirements}, {load_instructions}, {load_length}, {load_width}, {load_height}, {pickup_location}, {pickup_email}, {pickup_date}, {pickup_time}, {delivery_location}, {delivery_email}, {delivery_date}, {delivery_time}, {carrier_unloading}, {carrier_pallet_exchange}, {cargo_commodity}, {cargo_weight}',
            'emailtemplate_created' => '2019-12-08 17:13:10',
            'emailtemplate_updated' => '2021-01-25 18:32:01',
            'emailtemplate_status' => 'enabled',
            'emailtemplate_language' => 'english',
            'emailtemplate_real_template' => 'yes',
            'emailtemplate_show_enabled' => 'yes',
        );


        $temp=EmailTemplate::where('emailtemplate_name',$template['emailtemplate_name'])->first();
        if($temp)
        {
            $temp->delete();
        }
        $permission = new EmailTemplate($template);
        $permission->save();
    }
}