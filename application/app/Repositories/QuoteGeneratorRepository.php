<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for estimates
 * [Generate Quote]
 * All the logic and queries for fetching an quote. this allows a complete
 * quote to be called from inside any other controller/cornjob.
 *
 * The returned payload will contain all the data needed to show a web inovice
 * or to generate a PDF quote.
 *
 * [NOTE] This is not generating a PDF quote, just the quote object and
 * all related lineitems, payments etc
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Repositories\QuoteRepository;
use App\Repositories\LineitemRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UnitRepository;
use Log;

class QuoteGeneratorRepository {

    /**
     * The quote repository instance.
     */
    protected $quoterepo;

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
        QuoteRepository $quoterepo,
        TaxRepository $taxrepo,
        UnitRepository $unitrepo,
        LineitemRepository $lineitemrepo
    ) {

        $this->quoterepo = $quoterepo;
        $this->taxrepo = $taxrepo;
        $this->lineitemrepo = $lineitemrepo;
        $this->unitrepo = $unitrepo;

    }

    /**
     * generate and quote and return an array that contains the following
     *       - The quote object
     *       - tax rates
     *       - line items
     *       - elements
     *       - units
     * @param int $id quote id
     * @return array
     */
    public function generate($id = '') {

        //info
        Log::info("quote generation started", ['process' => '[QuoteGeneratorRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $id]);

        //search for the quote
        $quotes = $this->quoterepo->search($id);

        //get the quote
        if (!$bill = $quotes->first()) {
            Log::error("quote generation failed", ['process' => '[QuoteGeneratorRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $id]);
            return false;
        }

        //refresh quote
        $this->quoterepo->refreshQuote($bill);

        //generic json payload
        $bill->json = $this->quoteJson($bill);

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
            'taxresource_type' => 'quote',
            'taxresource_id' => $id,
        ]);
        $taxes = $this->taxrepo->search();

        //line items
        request()->merge([
            'lineitemresource_type' => 'quote',
            'lineitemresource_id' => $id,
        ]);
        $lineitems = $this->lineitemrepo->search();

        //reponse payload
        $payload = [
            'bill' => $this->quoteData($bill),
            'taxrates' => $taxrates,
            'units' => $this->unitrepo->search(),
            'taxes' => $bill->taxes,
            'lineitems' => $lineitems,
            'elements' => $elements,
        ];

        //info
        Log::info("quote generated successfully", ['process' => '[permissions]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //return the quote payload
        return $payload;
    }

    /**
     * return an array of 'enabled' tax rates, taking into account the system tax rates and
     * quotes own tax rates
     * @param object $quote instance of the quote model object
     * @return array
     */
    public function getTaxRates($quote) {

        //quotes set tax rates
        $used = [];
        foreach ($quote->taxes as $tax) {
            $used[] = $tax->tax_taxrateid;
        }

        //system tax rates
        $taxrates = \App\Models\Taxrate::get();
        foreach ($taxrates as $key => $taxrate) {
            //remove disabled taxes (which are not already used in this quote)
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
     * additional quote data
     * @param object $quote instance of the quote model object
     * @return array
     */
    private function quoteData($quote) {

        //visibility of some elements
        $quote->visibility_subtotal_row = ($quote->bill_discount_amount > 0 || $quote->bill_tax_total_amount > 0) ? '' : 'hidden';
        $quote->visibility_discount_row = ($quote->bill_discount_amount > 0) ? '' : 'hidden';
        $quote->visibility_tax_row = ($quote->bill_tax_total_amount > 0) ? '' : 'hidden';
        $quote->visibility_before_tax_row = ($quote->bill_discount_amount > 0) ? '' : 'hidden';
        $quote->visibility_adjustment_row = ($quote->bill_adjustment_amount > 0 || $quote->bill_adjustment_amount < 0) ? '' : 'hidden';


        return $quote;

    }

    /**
     * return a json string of specific fields that are needed in frontent
     * @param object $quote instance of the quote model object
     * @return array
     */
    private function quoteJson($quote) {

        //visibility of some elements
        $visibility_subtotal_row = ($quote->bill_discount_amount > 0 || $quote->bill_tax_total_amount > 0) ? '' : 'hidden';
        $visibility_discount_row = ($quote->bill_discount_amount > 0) ? '' : 'hidden';
        $visibility_tax_row = ($quote->bill_tax_total_amount > 0) ? '' : 'hidden';

        $data = [
            'final_amount' => $quote->bill_final_amount,
            'tax_type' => $quote->bill_tax_type,
            'tax_amount' => $quote->bill_tax_total_amount,
            'discount_type' => $quote->bill_discount_type,
            'discount_amount' => $quote->bill_discount_amount,
            'visibility_subtotal_row' => $visibility_subtotal_row,
            'visibility_discount_row' => $visibility_discount_row,
            'visibility_tax_row' => $visibility_tax_row,
        ];

        return json_encode($data);

    }

}