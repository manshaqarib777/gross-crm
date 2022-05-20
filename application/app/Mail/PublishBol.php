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
        if (!$this->obj instanceof \App\Models\Bol || !$this->user instanceof \App\Models\User) {
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

        //set template variables
        $payload += [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'bol_id' => runtimeBolIdFormat($this->obj->bill_bolid),
            'bol_amount' => runtimeMoneyFormat($this->obj->bill_final_amount),
            'bol_amount_due' => runtimeMoneyFormat($this->obj->bol_balance),
            'bol_date_created' => runtimeDate($this->obj->bill_date),
            'bol_date_due' => runtimeDate($this->obj->bill_due_date),
            'project_title' => $this->obj->project_title,
            'project_id' => $this->obj->project_id,
            'client_name' => $this->obj->client_company_name,
            'client_id' => $this->obj->client_id,
            'bol_status' => runtimeSystemLang($this->obj->bill_status),
            'bol_url' => url('/bols/' . $this->obj->bill_bolid),
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
        $queue->emailqueue_pdf_resource_id = $this->obj->bill_bolid;
        $queue->emailqueue_resourcetype = 'bol';
        $queue->emailqueue_resourceid = $this->obj->bill_bolid;
        $queue->save();
    }
}
