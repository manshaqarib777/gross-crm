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
            'emailtemplate_subject' => 'Rate Confirmtion - #{bol_id}',
            'emailtemplate_body' => '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>Email Confirmation</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
            </head>            
            <body>        
            </body>
            </html>',
            'emailtemplate_variables' => '{user_name}, {user_phone}, {user_email}, {user_fax}, {contact_mc_dot_number}, {contact_name}, {contact_phone}, {contact_term}, {contact_fax}, {contact_address}, {contact_dispatcher}, {contact_driver}, {contact_truck}, {contact_trailer}, {product_items}, {bol_amount}, {load_mode}, {load_trailer_type}, {load_trailer_size}, {load_linear_feet}, {load_temperature}, {load_pallet_case_count}, {load_hazmat}, {load_requirements}, {load_instructions}, {load_length}, {load_width}, {load_height}, {pickup_location}, {pickup_email}, {pickup_gstin}, {pickup_telefax}, {pickup_phone}, {delivery_location}, {delivery_email}, {delivery_gstin}, {delivery_telefax}, {delivery_phone}, {carrier_unloading}, {carrier_pallet_exchange}, {cargo_commodity}, {cargo_weight}',
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