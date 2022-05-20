<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for estimates
 * [Generate Bol]
 * All the logic and queries for fetching an bol. this allows a complete
 * bol to be called from inside any other controller/cornjob.
 *
 * The returned payload will contain all the data needed to show a web inovice
 * or to generate a PDF bol.
 *
 * [NOTE] This is not generating a PDF bol, just the bol object and
 * all related lineitems, payments etc
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Repositories\BolRepository;
use App\Repositories\LineitemRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UnitRepository;
use Log;

class BolGeneratorRepository {

    /**
     * The bol repository instance.
     */
    protected $bolrepo;

    /**
     * The tax repository instance.
     */
    protected $taxrepo;

    /**
     * The line item repository instance.
     */
    protected $lineitemrepo;

    /**
     * The unit item repository instance.
     */
    protected $unitrepo;

    /**
     * Inject dependecies
     */
    public function __construct(
        BolRepository $bolrepo,
        TaxRepository $taxrepo,
        UnitRepository $unitrepo,
        LineitemRepository $lineitemrepo
    ) {

        $this->bolrepo = $bolrepo;
        $this->taxrepo = $taxrepo;
        $this->lineitemrepo = $lineitemrepo;
        $this->unitrepo = $unitrepo;

    }

    /**
     * generate and bol and return an array that contains the following
     *       - The bol object
     *       - tax rates
     *       - line items
     *       - elements
     *       - units
     * @param int $id bol id
     * @return array
     */
    public function generate($id = '') {

        //info
        Log::info("bol generation started", ['process' => '[BolGeneratorRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $id]);

        //search for the bol
        $bols = $this->bolrepo->search($id);

        //get the bol
        if (!$bill = $bols->first()) {
            Log::error("bol generation failed", ['process' => '[BolGeneratorRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $id]);
            return false;
        }

        //refresh bol
        $this->bolrepo->refreshBol($bill);

        //generic json payload
        $bill->json = $this->bolJson($bill);

        //get tax rates
        $taxrates = $this->getTaxRates($bill);

        //tax html element - for popover
        $elements['tax_popover'] = htmlentities(view('pages/bill/components/elements/taxpopover', compact('bill', 'taxrates'))->render());

        //elements - discount popover
        $elements['discount_popover'] = htmlentities(view('pages/bill/components/elements/discounts', compact('bill'))->render());

        //elements - discount popover
        $elements['adjustments_popover'] = htmlentities(view('pages/bill/components/elements/adjustments', compact('bill'))->render());

        //get taxes
        request()->merge([
            'taxresource_type' => 'bol',
            'taxresource_id' => $id,
        ]);
        $taxes = $this->taxrepo->search();

        //line items
        request()->merge([
            'lineitemresource_type' => 'bol',
            'lineitemresource_id' => $id,
        ]);
        $lineitems = $this->lineitemrepo->search();

        //reponse payload
        $payload = [
            'bill' => $this->bolData($bill),
            'taxrates' => $taxrates,
            'units' => $this->unitrepo->search(),
            'taxes' => $bill->taxes,
            'lineitems' => $lineitems,
            'elements' => $elements,
        ];

        //info
        Log::info("bol generated successfully", ['process' => '[permissions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //return the bol payload
        return $payload;
    }

    /**
     * return an array of 'enabled' tax rates, taking into account the system tax rates and
     * bols own tax rates
     * @param object $bol instance of the bol model object
     * @return array
     */
    public function getTaxRates($bol) {

        //bols set tax rates
        $used = [];
        foreach ($bol->taxes as $tax) {
            $used[] = $tax->tax_taxrateid;
        }

        //system tax rates
        $taxrates = \App\Models\Taxrate::get();
        foreach ($taxrates as $key => $taxrate) {
            //remove disabled taxes (which are not already used in this bol)
            if ($taxrate->taxrate_status == 'disabled' && !in_array($taxrate->taxrate_id, $used)) {
                $taxrates->forget($key);
            }
            //mark selected
            if (in_array($taxrate->taxrate_id, $used)) {
                $taxrate->taxrate_selected = 'selected';
            }
        }

        return $taxrates;
    }

    /**
     * additional bol data
     * @param object $bol instance of the bol model object
     * @return array
     */
    private function bolData($bol) {

        //visibility of some elements
        $bol->visibility_subtotal_row = ($bol->bill_discount_amount > 0 || $bol->bill_tax_total_amount > 0) ? '' : 'hidden';
        $bol->visibility_discount_row = ($bol->bill_discount_amount > 0) ? '' : 'hidden';
        $bol->visibility_tax_row = ($bol->bill_tax_total_amount > 0) ? '' : 'hidden';
        $bol->visibility_before_tax_row = ($bol->bill_discount_amount > 0) ? '' : 'hidden';
        $bol->visibility_adjustment_row = ($bol->bill_adjustment_amount > 0 || $bol->bill_adjustment_amount < 0) ? '' : 'hidden';


        return $bol;

    }

    /**
     * return a json string of specific fields that are needed in frontent
     * @param object $bol instance of the bol model object
     * @return array
     */
    private function bolJson($bol) {

        //visibility of some elements
        $visibility_subtotal_row = ($bol->bill_discount_amount > 0 || $bol->bill_tax_total_amount > 0) ? '' : 'hidden';
        $visibility_discount_row = ($bol->bill_discount_amount > 0) ? '' : 'hidden';
        $visibility_tax_row = ($bol->bill_tax_total_amount > 0) ? '' : 'hidden';

        $data = [
            'final_amount' => $bol->bill_final_amount,
            'tax_type' => $bol->bill_tax_type,
            'tax_amount' => $bol->bill_tax_total_amount,
            'discount_type' => $bol->bill_discount_type,
            'discount_amount' => $bol->bill_discount_amount,
            'visibility_subtotal_row' => $visibility_subtotal_row,
            'visibility_discount_row' => $visibility_discount_row,
            'visibility_tax_row' => $visibility_tax_row,
        ];

        return json_encode($data);

    }

}