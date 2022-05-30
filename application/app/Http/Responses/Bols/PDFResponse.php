<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [pdf] process for the bols
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Bols;
use Illuminate\Contracts\Support\Responsable;
use PDF;

class PDFResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //[debugging purposes] view bol in browser (https://domain.com/bol/1/pdf?view=preview)
        if (request('view') == 'preview') {
            config(['css.bill_mode' => 'pdf-mode-preview']);
            return view('pages/bill/bill-pdf', compact('page', 'bill', 'taxrates', 'taxes', 'elements', 'lineitems', 'customfields'))->render();
        }

        $product_items ="";
        foreach ($lineitems as $key => $value) {
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
        $data = config('mail.data');

        $data += [
            'logo_url' => "https://redb1rd.com/storage/logos/app/".config('system.settings_system_logo_large_name'),
            'bol_id' => runtimeBolIdFormat($bill->bill_bolid),
            'user_name' => $bill->first_name." ".$bill->last_name,
            'user_phone' => $bill->phone,
            'user_email' => $bill->email,
            'user_fax' => $bill->fax,
            'contact_mc_dot_number' => $bill->contact_mc_dot_number,
            'contact_name' => $bill->contact_name,
            'contact_phone' => $bill->contact_phone,
            'contact_term' => $bill->contact_term,
            'contact_fax' => $bill->contact_fax,
            'contact_address' => $bill->contact_address,
            'contact_dispatcher' => $bill->contact_dispatcher,
            'contact_driver' => $bill->contact_driver,
            'contact_truck' => $bill->contact_truck,
            'contact_trailer' => $bill->contact_trailer,
            'product_items' => $product_items,
            'bol_amount' => runtimeMoneyFormat($bill->bill_final_amount),
            'load_mode' => $bill->load_mode,
            'load_trailer_type' => $bill->load_trailer_type,
            'load_trailer_size' => $bill->load_trailer_size,
            'load_linear_feet' => $bill->load_linear_feet,
            'load_temperature' => $bill->load_temperature,
            'load_pallet_case_count' => $bill->load_pallet_case_count,
            'load_hazmat' => $bill->load_hazmat,
            'load_requirements' => $bill->load_requirements,
            'load_instructions' => $bill->load_instructions,
            'load_length' => $bill->load_length,
            'load_width' => $bill->load_width,
            'load_height' => $bill->load_height,
            'pickup_location' => $bill->pickup_location,
            'pickup_email' => $bill->pickup_email,
            'pickup_gstin' => $bill->pickup_gstin,
            'pickup_date' => $bill->pickup_date,
            'pickup_time' => $bill->pickup_time,
            'delivery_location' => $bill->delivery_location,
            'delivery_email' => $bill->delivery_email,
            'delivery_gstin' => $bill->delivery_gstin,
            'delivery_date' => $bill->delivery_date,
            'delivery_time' => $bill->delivery_time,
            'carrier_unloading' => $bill->carrier_unloading,
            'carrier_pallet_exchange' => $bill->carrier_pallet_exchange,
            'cargo_commodity' => $bill->cargo_commodity,
            'cargo_weight' => $bill->cargo_weight,
            'bol_amount_due' => runtimeMoneyFormat($bill->bol_balance),
            'bol_date_created' => runtimeDate($bill->bill_date),
            'bol_date_due' => runtimeDate($bill->bill_due_date),
            'project_title' => $bill->project_title,
            'project_id' => $bill->project_id,
            'client_name' => $bill->client_company_name,
            'client_id' => $bill->client_id,
            'bol_status' => runtimeSystemLang($bill->bill_status),
            'bol_url' => url('/bols/' . $bill->bill_bolid),
        ];

        //email template
        if (!$template = \App\Models\EmailTemplate::Where('emailtemplate_name', 'New Bol')->first()) {
            return false;
        }
        $payload =$template->parse('body', $data);
        //dd($bill);

        //download pdf view
        config(['css.bill_mode' => 'pdf-mode-download']);
        $pdf = PDF::loadView('pages/bill/bill-pdf-bol', compact( 'payload'));
        $filename = "Rate Confirmation for ". $bill->client_company_name . '.pdf'; //bol_inv0001.pdf
        return $pdf->download($filename);
    }
}
