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
            'emailtemplate_subject' => 'Rate Confirmation for {user_name}',
            'emailtemplate_body' => '<!DOCTYPE html>
            <html lang="en" style="width: 100%;
            height: 100%;
            padding-top: 0;
            margin-top: 0;">
            
            <head>
                <meta charset="utf-8">
                <title>Email Confirmation</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
            
            </head>
            
            <body
                style="margin: 0;font-family: Verdana, sans-serif;font-size: 1rem;font-weight: 400;color: #212529;text-align: left;background-color: #fff;font-size:10px;">
            
            
                <div>
                    <table style="width: 100%;color: #212529;" class="table table-bordered mb-1">
                        <thead>
                            <tr>
                                <th
                                    style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                    <h1 class="page-title text-secondary-d1"
                                        style="padding: 0;margin: 0;">
                                        <img src="{logo_url}" height="50px" width="200px" />
                                    </h1>
                                </th>
                                <th
                                    style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:right;border: none !important;font-weight: 800;">
                                    <div class="page-tools">
                                        <div class="action-buttons">
                                            <h2 style="font-weight: 800;text-align: right !important;">RATE CONFIRMATION FOR PO#
                                                {bol_id}</h2>
                                            <h4 style="font-weight: 800;text-align: right !important;margin: 0px;">FIND YOUR NEXT
                                                LOAD BY VISITING</h4>
                                            <h4 style="font-weight: 800;text-align: right !important;margin: 0px;color: #f65a6b !important;">
                                                <a href="https://redb1rd.com">redb1rd.com</a>
                                                </h4>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                    </table>
            
            
                    <div class="container px-0">
                        <div class="row mt-4">
                            <div class="col-12 col-lg-12">
            
                                <h6 class="font-weight-bold mx-1px text-95 w-60" style="color: #f65a6b !important;width: 320px;margin-top:0px;margin-bottom:0px;">
                                    TO ENSURE PROMPT PAYMENT, SUBMIT THIS RATE CONFIRMATION, COMPLETE BOL(S)/POD,RECEIPTS AND OTHER
                                    APPLICABLE PAPERWORK TO CINVOICES@REDB1RD.COM. FOR OTHER OPTIONS, SEE NEXT PAGE.
                                </h6>
                                <h3 style="width:25%;text-align:center;padding:6px;color:white;background-color: #f65a6b !important;"
                                    class="bgc-default-tp1 text-600 text-white text-center p-1">REDB1RD CONTACT INFO</h3>
                                <table style="width: 100%;color: #212529;" class="table table-bordered mb-1">
                                    <thead>
                                        <tr>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Name</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Phone</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Email</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Fax</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <tr>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {user_name}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {user_phone}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {user_email}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {user_fax}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h3 style="width:25%;text-align:center;padding:6px;color:white;background-color: #f65a6b !important;"
                                    class="bgc-default-tp1 text-600 text-white text-center p-1">CARRIER CONTACT</h3>
                                <table style="width: 100%;color: #212529;" class="table table-bordered mb-1">
                                    <thead>
                                        <tr>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                MC#/DOT#</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Name</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Phone</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Terms</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Fax</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <tr>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {contact_mc_dot_number}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {contact_name}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {contact_phone}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {contact_term}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {contact_fax}</td>
                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th colspan="5"
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Address</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <tr>
                                            <td style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;"
                                                colspan="5">{contact_address}</td>
                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;"
                                                colspan="2">Dispatcher</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Driver</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Truck #</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Trailer #</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <tr>
                                            <td style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;"
                                            colspan="2">{contact_dispatcher}</td>
                                            <td style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;"
                                            >{contact_driver}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {contact_truck}</td>
                                            <td
                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                {contact_trailer}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h3 style="width:25%;text-align:center;padding:6px;color:white;background-color: #f65a6b !important;"
                                    class="bgc-default-tp1 text-600 text-white text-center p-1">LOAD INFORMATION</h3>
                                <table style="width: 100%;color: #212529;" class="table table-bordered mb-1">
                                    <thead>
                                        <tr>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Rate</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Type</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Unit</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Quantity</th>
                                            <th
                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        {product_items}
                                    </tbody>
                                </table>
            
                                <div class="row mt-3">
                                    <table style="width: 100%;color: #212529;"
                                            class="table table-bordered mb-1">
                                            <thead>
                                                <tr>
                                                    <td
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;">
                                                        Rates that are based on weight or count will be calculated from the quantities loaded.</td>
                                                    <th style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:right;border: none !important;font-weight: 800;font-size: 15px;color: #f65a6b !important;">
                                                            Total : {bol_amount} USD
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
            
                                    <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                        <table style="width: 100%;color: #212529;"
                                            class="table table-bordered mb-1">
                                            <thead>
                                                <tr>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Mode</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Trailer Type</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Trailer Size</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Linear Feet</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Temperature</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Pallet/Case Count</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Hazmat</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Load Requirements</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_mode}</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_trailer_type}</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_trailer_size}</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_linear_feet}
                                                    </td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_temperature}
                                                    </td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_pallet_case_count} </td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_hazmat}</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_requirements}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;"
                                                        class="font-weight-bold" colspan="2">Special Temp Instructions</td>
                                                    <td style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;"
                                                        colspan="4">
                                                        {load_instructions}
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;"
                                                        class="font-weight-bold">LxWxH</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {load_length} * {load_width} * {load_height}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table style="width: 100%;color: #212529;"
                                            class="table table-bordered mb-1">
                                            <thead>
                                                <tr>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Pick-up Location</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Email</th>
                                                   <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Date</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Time</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {pickup_location} </td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {pickup_email}</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {pickup_date}</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {pickup_time}</td>
                                                </tr>
                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Delivery Location</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Email</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Date</th>
                                                    <th
                                                        style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;border: none !important;font-weight: 800;">
                                                        Time</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {delivery_location} </td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {delivery_email}</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {delivery_date}</td>
                                                    <td
                                                        style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                        {delivery_time}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-4 col-sm-4">
                                                <h3 style="width:25%;text-align:center;padding:6px;color:white;background-color: #f65a6b !important;"
                                                    class="bgc-default-tp1 text-600 text-white text-center p-1">CARRIER RESPONSIBLE
                                                    FOR</h3>
                                                <table style="width: 100%;color: #212529;border: none !important;">
                                                    <tbody >
                                                        <tr>
                                                            <td
                                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;border: none !important;font-weight: 800;">
                                                                Unloading </td>
                                                            <td
                                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                                {carrier_unloading}</td>
                                                            <td
                                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;border: none !important;font-weight: 800;">
                                                                Pallet Exchange</td>
                                                            <td
                                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                                {carrier_pallet_exchange}</td>
                                                            <td
                                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;border: none !important;font-weight: 800;">
                                                                Estimated Commodity</td>
                                                            <td
                                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                                {cargo_commodity}</td>
                                                            <td
                                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;border: none !important;font-weight: 800;">
                                                                Estimated Weight</td>
                                                            <td
                                                                style="border: 1px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                                                {cargo_weight}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table style="width: 100%;color: #212529;">
                                                    <tbody >
                                                        <tr>
                                                            <th
                                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;color: #f65a6b;">
                                                                Note to Carrier </th>
                                                            <th
                                                                style="border: 1px solid #dee2e6;vertical-align: bottom;border-bottom: 2px solid #dee2e6;padding: 0.5rem;vertical-align: top;border-top: 1px solid #dee2e6;text-align:left;background: yellow;">
                                                                Late delivery may result in
                                                                non-payment of freight charges, and special damages may apply. This
                                                                includes, but is
                                                                not
                                                                limited to, freight charges for expedited shipments, excessive late
                                                                fees, additional
                                                                labor charges, storage charges, loss of
                                                                sale, the expense of any additional equipment, service, or alternate
                                                                transportation
                                                                arrangements that need to be utilized as
                                                                a result of late delivery. STRAPS & ETRACK REQUIRED. DRIVER MUST
                                                                SECURE FREIGHT. POD
                                                                DUE UPON
                                                                DELIVERY. DRIVER MUST ACCEPT TRACKING.</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </body>
            
            </html>',
            'emailtemplate_variables' => '{user_name}, {user_phone}, {user_email}, {user_fax}, {contact_mc_dot_number}, {contact_name}, {contact_phone}, {contact_term}, {contact_fax}, {contact_address}, {contact_dispatcher}, {contact_driver}, {contact_truck}, {contact_trailer}, {product_items}, {bol_amount}, {load_mode}, {load_trailer_type}, {load_trailer_size}, {load_linear_feet}, {load_temperature}, {load_pallet_case_count}, {load_hazmat}, {load_requirements}, {load_instructions}, {load_length}, {load_width}, {load_height}, {pickup_location}, {pickup_email}, {pickup_phone}, {delivery_location}, {delivery_email}, {delivery_phone}, {carrier_unloading}, {carrier_pallet_exchange}, {cargo_commodity}, {cargo_weight}',
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