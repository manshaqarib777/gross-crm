<?php

/** --------------------------------------------------------------------------------
 * This classes renders the [publish bol] email and stores it in the queue
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class PublishBol extends Mailable {
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
        if (!$template = \App\Models\EmailTemplate::Where('emailtemplate_name', 'New Bol')->first()) {
            return false;
        }

        //validate
        if (!$this->obj["bill"] instanceof \App\Models\Bol || !$this->user instanceof \App\Models\User) {
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
            'logo_url' => url('/')."/../storage/logos/app/".config('system.settings_system_logo_large_name'),
            'bol_id' => runtimeBolIdFormat($this->obj["bill"]->bill_bolid),
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
            'bol_amount' => runtimeMoneyFormat($this->obj["bill"]->bill_final_amount),
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
            'pickup_location' => $this->obj["bill"]->pickup_location,
            'pickup_email' => $this->obj["bill"]->pickup_email,
            'pickup_gstin' => $this->obj["bill"]->pickup_gstin,
            'pickup_date' => $this->obj["bill"]->pickup_date,
            'pickup_time' => $this->obj["bill"]->pickup_time,
            'delivery_location' => $this->obj["bill"]->delivery_location,
            'delivery_email' => $this->obj["bill"]->delivery_email,
            'delivery_gstin' => $this->obj["bill"]->delivery_gstin,
            'delivery_date' => $this->obj["bill"]->delivery_date,
            'delivery_time' => $this->obj["bill"]->delivery_time,
            'carrier_unloading' => $this->obj["bill"]->carrier_unloading,
            'carrier_pallet_exchange' => $this->obj["bill"]->carrier_pallet_exchange,
            'cargo_commodity' => $this->obj["bill"]->cargo_commodity,
            'cargo_weight' => $this->obj["bill"]->cargo_weight,
            'bol_amount_due' => runtimeMoneyFormat($this->obj["bill"]->bol_balance),
            'bol_date_created' => runtimeDate($this->obj["bill"]->bill_date),
            'bol_date_due' => runtimeDate($this->obj["bill"]->bill_due_date),
            'project_title' => $this->obj["bill"]->project_title,
            'project_id' => $this->obj["bill"]->project_id,
            'client_name' => $this->obj["bill"]->client_company_name,
            'client_id' => $this->obj["bill"]->client_id,
            'bol_status' => runtimeSystemLang($this->obj["bill"]->bill_status),
            'bol_url' => url('/bols/' . $this->obj["bill"]->bill_bolid),
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
        $queue->emailqueue_pdf_resource_type = 'bol';
        $queue->emailqueue_pdf_resource_id = $this->obj["bill"]->bill_bolid;
        $queue->emailqueue_resourcetype = 'bol';
        $queue->emailqueue_resourceid = $this->obj["bill"]->bill_bolid;
        $queue->save();
    }
}
