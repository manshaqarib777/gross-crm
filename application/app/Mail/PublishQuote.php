<?php

/** --------------------------------------------------------------------------------
 * This classes renders the [publish quote] email and stores it in the queue
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class PublishQuote extends Mailable {
    use Queueable;

    /**
     * The data for merging into the email
     */
    public $data;

    /**
     * Model instance
     */
    public $obj;

    /**
     * Model instance
     */
    public $user;

    public $emailerrepo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user = [], $data = [], $obj = []) {

        $this->data = $data;
        $this->user = $user;
        $this->obj = $obj;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        //email template
        if (!$template = \App\Models\EmailTemplate::Where('emailtemplate_name', 'New Quote')->first()) {
            return false;
        }

        //validate
        if (!$this->obj["bill"] instanceof \App\Models\Quote || !$this->user instanceof \App\Models\User) {
            return false;
        }

        //only active templates
        if ($template->emailtemplate_status != 'enabled') {
            return false;
        }

        //check if clients emails are disabled
        if ($this->user->type == 'client' && config('system.settings_clients_disable_email_delivery') == 'enabled') {
            return;
        }

        //get common email variables
        $payload = config('mail.data');
        //dd($this->obj["lineitems"]);
        $product_items ="";
        foreach ($this->obj["lineitems"] as $key => $value) {
            $product_items .=   '<tr>
                                    <td
                                        style="border-bottom-width: 2px;border: 1px solid #dee2e6;padding: 0.75rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                        '.runtimeMoneyFormat($value->lineitem_rate).'</td>
                                    <td
                                        style="border-bottom-width: 2px;border: 1px solid #dee2e6;padding: 0.75rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                        '.$value->lineitem_description.'</td>
                                    <td
                                        style="border-bottom-width: 2px;border: 1px solid #dee2e6;padding: 0.75rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                        '.$value->lineitem_unit.'</td>
                                    <td
                                        style="border-bottom-width: 2px;border: 1px solid #dee2e6;padding: 0.75rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                        '.$value->lineitem_quantity.'</td>
                                    <td
                                        style="border-bottom-width: 2px;border: 1px solid #dee2e6;padding: 0.75rem;vertical-align: top;border-top: 1px solid #dee2e6;">
                                        '.runtimeMoneyFormat($value->lineitem_total).'</td>
                                </tr>';
        }
        //set template variables
        $payload += [
            'logo_url' => url('/')."/storage/logos/app/".config('system.settings_system_logo_large_name'),
            'quote_status_color' => ($this->obj["bill"]->bill_status == "due" ? "#ff9041": ($this->obj["bill"]->bill_status == "draft" ? "#2f3d4a": ($this->obj["bill"]->bill_status == "overdue" ? "#ff5c6c": ($this->obj["bill"]->bill_status == "paid" ? "#24d2b5": ($this->obj["bill"]->bill_status == "part_paid" ? "#20aee3": "#2f3d4a" ) ) ) ) ),
            'quote_status' => ($this->obj["bill"]->bill_status == "due" ? cleanLang(__('lang.due')): ($this->obj["bill"]->bill_status == "draft" ? cleanLang(__('lang.draft')): ($this->obj["bill"]->bill_status == "overdue" ? cleanLang(__('lang.overdue')): ($this->obj["bill"]->bill_status == "paid" ? cleanLang(__('lang.paid')): cleanLang(__('lang.draft')) ) ) ) ),
            'quote_name' => cleanLang(__('lang.quote')),
            'quote_id' => runtimeQuoteIdFormat($this->obj["bill"]->bill_quoteid),
            'settings_company_name' => config('system.settings_company_name'),
            'settings_company_address_line_1' => config('system.settings_company_address_line_1'),
            'settings_company_state' => config('system.settings_company_state'),
            'settings_company_city' => config('system.settings_company_city'),
            'settings_company_zipcode' => config('system.settings_company_zipcode'),
            'settings_company_country' => config('system.settings_company_country'),
            'client_company_name' => $this->obj["bill"]->client_company_name,
            'client_billing_street' => $this->obj["bill"]->client_billing_street,
            'client_billing_city' => $this->obj["bill"]->client_billing_city,
            'client_billing_state' => $this->obj["bill"]->client_billing_state,
            'client_billing_zip' => $this->obj["bill"]->client_billing_zip,
            'client_billing_country' => $this->obj["bill"]->client_billing_country,


            'pickup_location' => cleanLang(__('lang.pickup_location')),
            'pickup_location' => cleanLang(__('lang.pickup_location')),
            'pickup_email' => cleanLang(__('lang.pickup_email')),
            'pickup_gstin' => cleanLang(__('lang.pickup_gstin')),
            'pickup_telefax' => cleanLang(__('lang.pickup_telefax')),
            'pickup_phone' => cleanLang(__('lang.pickup_phone')),
            'delivery_location' => cleanLang(__('lang.delivery_location')),
            'delivery_email' => cleanLang(__('lang.delivery_email')),
            'delivery_gstin' => cleanLang(__('lang.delivery_gstin')),
            'delivery_telefax' => cleanLang(__('lang.delivery_telefax')),
            'delivery_phone' => cleanLang(__('lang.delivery_phone')),
            'pickup_location_name' => $this->obj["bill"]->pickup_location,
            'pickup_location_name' => $this->obj["bill"]->pickup_location,
            'pickup_email_name' => $this->obj["bill"]->pickup_email,
            'pickup_gstin_name' => $this->obj["bill"]->pickup_gstin,
            'pickup_telefax_name' => $this->obj["bill"]->pickup_telefax,
            'pickup_phone_name' => $this->obj["bill"]->pickup_phone,
            'delivery_location_name' => $this->obj["bill"]->delivery_location,
            'delivery_email_name' => $this->obj["bill"]->delivery_email,
            'delivery_gstin_name' => $this->obj["bill"]->delivery_gstin,
            'delivery_telefax_name' => $this->obj["bill"]->delivery_telefax,
            'delivery_phone_name' => $this->obj["bill"]->delivery_phone,
            'quote_date' => cleanLang(__('lang.quote_date')),
            'quote_date_name' =>runtimeDate($this->obj["bill"]->bill_date),
            'contact_person' => cleanLang(__('lang.contact_person')),
            'contact_person_name' =>$this->obj["bill"]->contact_person,
            'contact_details' => cleanLang(__('lang.contact_details')),
            'contact_details_name' =>$this->obj["bill"]->contact_details,
            'cargo_commodity' => cleanLang(__('lang.cargo_commodity')),
            'cargo_commodity_name' =>$this->obj["bill"]->cargo_commodity,
            'cargo_weight' => cleanLang(__('lang.cargo_weight')),
            'cargo_weight_name' =>$this->obj["bill"]->cargo_weight,
            'bill_final_amount' => cleanLang(__('lang.total')),
            'bill_final_amount_name' =>runtimeMoneyFormat($this->obj["bill"]->bill_final_amount),
            'bill_terms' => cleanLang(__('lang.terms')),
            'bill_terms_name' =>$this->obj["bill"]->bill_terms,
            'user_name' => $this->user->first_name." ".$this->user->last_name,
            'user_phone' => $this->user->phone,
            'user_email' => $this->user->email,
            'user_fax' => $this->user->fax,
            'contact_mc_dot_number' => $this->obj["bill"]->contact_mc_dot_number,
            'contact_name' => $this->obj["bill"]->contact_name,
            'contact_phone' => $this->obj["bill"]->contact_phone,
            'contact_term' => $this->obj["bill"]->contact_term,
            'contact_fax' => $this->obj["bill"]->contact_fax,
            'contact_address' => $this->obj["bill"]->contact_address,
            'contact_dispatcher' => $this->obj["bill"]->contact_dispatcher,
            'contact_driver' => $this->obj["bill"]->contact_driver,
            'contact_truck' => $this->obj["bill"]->contact_truck,
            'contact_trailer' => $this->obj["bill"]->contact_trailer,
            'product_items' => $product_items,
            'quote_amount' => runtimeMoneyFormat($this->obj["bill"]->bill_final_amount),
            'load_mode' => $this->obj["bill"]->load_mode,
            'load_trailer_type' => $this->obj["bill"]->load_trailer_type,
            'load_trailer_size' => $this->obj["bill"]->load_trailer_size,
            'load_linear_feet' => $this->obj["bill"]->load_linear_feet,
            'load_temperature' => $this->obj["bill"]->load_temperature,
            'load_pallet_case_count' => $this->obj["bill"]->load_pallet_case_count,
            'load_hazmat' => $this->obj["bill"]->load_hazmat,
            'load_requirements' => $this->obj["bill"]->load_requirements,
            'load_instructions' => $this->obj["bill"]->load_instructions,
            'load_length' => $this->obj["bill"]->load_length,
            'load_width' => $this->obj["bill"]->load_width,
            'load_height' => $this->obj["bill"]->load_height,
            'carrier_unloading' => $this->obj["bill"]->carrier_unloading,
            'carrier_pallet_exchange' => $this->obj["bill"]->carrier_pallet_exchange,
            'quote_amount_due' => runtimeMoneyFormat($this->obj["bill"]->quote_balance),
            'quote_date_created' => runtimeDate($this->obj["bill"]->bill_date),
            'quote_date_due' => runtimeDate($this->obj["bill"]->bill_due_date),
            'project_title' => $this->obj["bill"]->project_title,
            'project_id' => $this->obj["bill"]->project_id,
            'client_name' => $this->obj["bill"]->client_company_name,
            'client_id' => $this->obj["bill"]->client_id,
            'quote_status' => runtimeSystemLang($this->obj["bill"]->bill_status),
            'quote_url' => url('/quotes/' . $this->obj["bill"]->bill_quoteid),
        ];
        $email = $this->user->email;
        if(isset($this->data["email"])&& $this->data["email"] != "")
        {
            $email = $this->data["email"];
        }
        $cc = "";
        if(isset($this->data["cc"])&& $this->data["cc"] != "")
        {
            $cc = $this->data["cc"];
        }
        //save in the database queue
        $queue = new \App\Models\EmailQueue();
        $queue->emailqueue_to = $email;
        $queue->emailqueue_cc = $cc;
        $queue->emailqueue_subject = $template->parse('subject', $payload);
        $queue->emailqueue_message = $template->parse('body', $payload);
        $queue->emailqueue_type = 'pdf';
        $queue->emailqueue_pdf_resource_type = 'quote';
        $queue->emailqueue_pdf_resource_id = $this->obj["bill"]->bill_quoteid;
        $queue->emailqueue_resourcetype = 'quote';
        $queue->emailqueue_resourceid = $this->obj["bill"]->bill_quoteid;
        $queue->save();
    }
}
