<div id="bill-form-container">
    <div class="card card-body invoice-wrapper box-shadow" id="invoice-wrapper">

        <!--HEADER-->
        @if($bill->bill_type == 'invoice')
        @include('pages.bill.components.elements.invoice.header-web')
        @endif
        @if($bill->bill_type == 'quote')
        @include('pages.bill.components.elements.quote.header-web')
        @endif
        @if($bill->bill_type == 'bol')
        @include('pages.bill.components.elements.bol.header-web')
        @endif
        @if($bill->bill_type == 'estimate')
        @include('pages.bill.components.elements.estimate.header-web')
        @endif

        <hr>
        <div class="row">
            <!--ADDRESSES-->
            <div class="col-12 m-b-10">
                <!--company address-->
                <div class="pull-left">
                    <address>
                        <h3 class="x-company-name text-info">{{ config('system.settings_company_name') }}</h3>
                        <p class="text-muted m-l-5">
                            @if(config('system.settings_company_address_line_1'))
                            {{ config('system.settings_company_address_line_1') }}
                            @endif
                            @if(config('system.settings_company_state'))
                            <br />{{ config('system.settings_company_state') }}
                            @endif
                            @if(config('system.settings_company_city'))
                            <br /> {{ config('system.settings_company_city') }}
                            @endif
                            @if(config('system.settings_company_zipcode'))
                            <br /> {{ config('system.settings_company_zipcode') }}
                            @endif
                            @if(config('system.settings_company_country'))
                            <br /> {{ config('system.settings_company_country') }}
                            @endif

                            <!--custom company fields-->
                            @if(config('system.settings_company_customfield_1') != '')
                            <br /> {{ config('system.settings_company_customfield_1') }}
                            @endif
                            @if(config('system.settings_company_customfield_2') != '')
                            <br /> {{ config('system.settings_company_customfield_2') }}
                            @endif
                            @if(config('system.settings_company_customfield_3') != '')
                            <br /> {{ config('system.settings_company_customfield_3') }}
                            @endif
                            @if(config('system.settings_company_customfield_4') != '')
                            <br /> {{ config('system.settings_company_customfield_4') }}
                            @endif
                        </p>
                    </address>
                </div>
                <!--client address-->
                <div class="pull-right text-right">
                    <address>
                        <h3 class="">{{ cleanLang(__('lang.bill_to')) }}</h3>
                        <a href="{{ url('clients/'.$bill->client_id) }}">
                            <h4 class="font-bold">{{ $bill->client_company_name }}</h4>
                        </a>
                        <p class="text-muted m-l-30">
                            @if($bill->client_billing_street)
                            {{ $bill->client_billing_street }}
                            @endif
                            @if($bill->client_billing_city)
                            <br /> {{ $bill->client_billing_city }}
                            @endif
                            @if($bill->client_billing_state)
                            <br /> {{ $bill->client_billing_state }}
                            @endif
                            @if($bill->client_billing_zip)
                            <br /> {{ $bill->client_billing_zip }}
                            @endif
                            @if($bill->client_billing_country)
                            <br /> {{ $bill->client_billing_country }}
                            @endif

                            <!--custom fields-->
                            @foreach($customfields as $field)
                            @if($field->customfields_show_invoice == 'yes')
                            <br /> {{ strip_tags(customFieldValue($field->customfields_name, $bill, $field->customfields_datatype)) }}
                            @endif
                            @endforeach
                        </p>
                    </address>
                </div>
            </div>

            <!--DATES & AMOUNT DUE-->
            @if($bill->bill_type == 'invoice')
            <div class="col-12 m-b-10" id="invoice-dates-wrapper">
                @include('pages.bill.components.elements.invoice.dates')
                @include('pages.bill.components.elements.invoice.payments')
            </div>
            @endif
            @if($bill->bill_type == 'quote')
            {{-- <div class="col-12 m-b-10" id="quote-dates-wrapper">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.carrier_info_half')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.carrier_info_full')
                   </div>
                </div>
            </div>

            <div class="col-12 m-b-10" id="quote-dates-wrapper">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.load_info_half')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.load_info_full')
                   </div>
                </div>
            </div>

            <div class="col-12 m-b-10" id="quote-dates-wrapper">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.carrier_responsible_half')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.carrier_responsible_full')
                   </div>
                </div>
            </div> --}}

            <div class="col-12 m-b-10" id="quote-dates-wrapper">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.pickup_location')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.delivery_location')
                   </div>
                </div>
            </div>
            <div class="col-12 m-b-10" id="quote-dates-wrapper">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.contacts')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.quote.comodity')
                   </div>
                </div>
            </div>
            <div class="col-12 m-b-10" id="quote-dates-wrapper">
                @include('pages.bill.components.elements.quote.dates')
                @include('pages.bill.components.elements.quote.payments')

            </div>
            @endif
            @if($bill->bill_type == 'bol')

            <div class="col-12 m-b-10">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.bol.carrier_info_half')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.bol.carrier_info_full')
                   </div>
                </div>
            </div>

            <div class="col-12 m-b-10">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.bol.load_info_half')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.bol.load_info_full')
                   </div>
                </div>
            </div>

            <div class="col-12 m-b-10">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.bol.carrier_responsible_half')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.bol.carrier_responsible_full')
                   </div>
                </div>
            </div>
            
            <div class="col-12 m-b-10">
                <div class="row">
                   <div class="col-6">
                        @include('pages.bill.components.elements.bol.pickup_location')
                   </div>
                   <div class="col-6">
                        @include('pages.bill.components.elements.bol.delivery_location')
                   </div>
                </div>
            </div>
            <div class="col-12 m-b-10">
                @include('pages.bill.components.elements.bol.dates')
                @include('pages.bill.components.elements.bol.payments')

            </div>
            @endif
            @if($bill->bill_type == 'estimate')
            <div class="col-12 m-b-10" id="invoice-dates-wrapper">
                @include('pages.bill.components.elements.estimate.dates')
            </div>
            @endif


            <!--INVOICE TABLE-->
            @include('pages.bill.components.elements.main-table')


            <!--[EDITING] INVOICE LINE ITEMS BUTTONS -->
            @if(config('visibility.bill_mode') == 'editing')
            <div class="col-12">
                @include('pages.bill.components.misc.add-line-buttons')
            </div>
            @endif


            <!-- TOTAL & SUMMARY -->
            @include('pages.bill.components.elements.totals-table')

            <!-- TAXES & DISCOUNTS -->
            @if(config('visibility.bill_mode') == 'editing')
            @include('pages.bill.components.elements.taxes-discounts')
            @endif

            <!--[VIEWING] INVOICE TERMS & MAKE PAYMENT BUTTON-->
            @if(config('visibility.bill_mode') == 'viewing')
            <div class="col-12">
                <!--invoice terms-->
                <div class="text-left">
                    @if($bill->bill_type == 'invoice')
                    <h4>{{ cleanLang(__('lang.invoice_terms')) }}</h4>
                    @elseif($bill->bill_type == 'quote')
                    <h4>{{ cleanLang(__('lang.quote_terms')) }}</h4>
                    @elseif($bill->bill_type == 'bol')
                    <h4>{{ cleanLang(__('lang.bol_terms')) }}</h4>
                    @else
                    <h4>{{ cleanLang(__('lang.estimate_terms')) }}</h4>
                    @endif
                    <div id="invoice-terms">{!! clean($bill->bill_terms) !!}</div>
                </div>
                <!--client - make a payment button-->
                @if(auth()->user()->is_client)
                <hr>
                <div class="p-t-25 invoice-pay" id="invoice-buttons-container">
                    <div class="text-right">
                        <!--[invoice] download pdf-->
                        @if($bill->bill_type == 'invoice')
                        <a class="btn btn-secondary btn-outline"
                            href="{{ url('/invoices/'.$bill->bill_invoiceid.'/pdf') }}" download>
                            <span><i class="mdi mdi-download"></i> {{ cleanLang(__('lang.download')) }}</span> </a>
                        @else
                        <!--[estimate] download pdf-->
                        <a class="btn btn-secondary btn-outline"
                            href="{{ url('/estimates/'.$bill->bill_estimateid.'/pdf') }}" download>
                            <span><i class="mdi mdi-download"></i> {{ cleanLang(__('lang.download')) }}</span> </a>
                        @endif
                        <!--[invoice] - make payment-->
                        @if($bill->bill_type == 'invoice' && $bill->invoice_balance > 0)
                        <button class="btn btn-danger" id="invoice-make-payment-button">
                            {{ cleanLang(__('lang.make_a_payment')) }} </button>
                        @endif

                        <!--accept or decline-->
                        @if(in_array($bill->bill_status, ['new', 'revised']))
                        <!--decline-->
                        <button class="buttons-accept-decline btn btn-danger confirm-action-danger"
                            data-confirm-title="{{ cleanLang(__('lang.decline_estimate')) }}"
                            data-confirm-text="{{ cleanLang(__('lang.decline_estimate_confirm')) }}"
                            data-ajax-type="GET"
                            data-url="{{ url('/') }}/estimates/{{ $bill->bill_estimateid }}/decline">
                            {{ cleanLang(__('lang.decline_estimate')) }} </button>
                        <!--accept-->
                        <button class="buttons-accept-decline btn btn-success confirm-action-success"
                            data-confirm-title="{{ cleanLang(__('lang.accept_estimate')) }}"
                            data-confirm-text="{{ cleanLang(__('lang.accept_estimate_confirm')) }}" data-ajax-type="GET"
                            data-url="{{ url('/') }}/estimates/{{ $bill->bill_estimateid }}/accept">
                            {{ cleanLang(__('lang.accept_estimate')) }} </button>
                        @endif


                    </div>
                    @endif

                </div>
                <!--payment buttons-->
                @include('pages.pay.buttons')
                @endif


                <!--[EDITING] INVOICE TERMS & MAKE PAYMENT BUTTON-->
                @if(config('visibility.bill_mode') == 'editing')
                <div class="col-12">
                    <!--invoice terms-->
                    <div class="text-left">
                        @if($bill->bill_type == 'invoice')
                        <h4>{{ cleanLang(__('lang.invoice_terms')) }}</h4>
                        @elseif($bill->bill_type == 'quote')
                        <h4>{{ cleanLang(__('lang.quote_terms')) }}</h4>
                        @elseif($bill->bill_type == 'bol')
                        <h4>{{ cleanLang(__('lang.bol_terms')) }}</h4>
                        @else
                        <h4>{{ cleanLang(__('lang.estimate_terms')) }}</h4>
                        @endif
                        <textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="bill_terms"
                            id="bill_terms">{!! clean($bill->bill_terms) !!}</textarea>
                    </div>
                    <!--client - make a payment button-->
                    <div class="text-right p-t-25">
                        @if($bill->bill_type == 'invoice')
                        <!--cancel-->
                        <a class="btn btn-secondary btn-sm"
                            href="{{ url('/invoices/'.$bill->bill_invoiceid) }}">Cancel</a>
                        <!--save changes-->
                        <button class="btn btn-danger btn-sm"
                            data-url="{{ url('/invoices/'.$bill->bill_invoiceid.'/edit-invoice') }}" data-type="form"
                            data-form-id="bill-form-container" data-ajax-type="post" id="billing-save-button">
                            {{ cleanLang(__('lang.save_changes')) }}
                        </button>
                        @elseif($bill->bill_type == 'quote')
                        <!--cancel-->
                        <a class="btn btn-secondary btn-sm"
                            href="{{ url('/quotes/'.$bill->bill_quoteid) }}">Cancel</a>
                        <!--save changes-->
                        <button class="btn btn-danger btn-sm"
                            data-url="{{ url('/quotes/'.$bill->bill_quoteid.'/edit-quote') }}" data-type="form"
                            data-form-id="bill-form-container" data-ajax-type="post" id="billing-save-button">
                            {{ cleanLang(__('lang.save_changes')) }}
                        </button>
                        @elseif($bill->bill_type == 'bol')
                        <!--cancel-->
                        <a class="btn btn-secondary btn-sm"
                            href="{{ url('/bols/'.$bill->bill_bolid) }}">Cancel</a>
                        <!--save changes-->
                        <button class="btn btn-danger btn-sm"
                            data-url="{{ url('/bols/'.$bill->bill_bolid.'/edit-bol') }}" data-type="form"
                            data-form-id="bill-form-container" data-ajax-type="post" id="billing-save-button">
                            {{ cleanLang(__('lang.save_changes')) }}
                        </button>
                        @else
                        <a class="btn btn-secondary btn-sm"
                            href="{{ url('/estimates/'.$bill->bill_estimateid) }}">{{ cleanLang(__('lang.cancel')) }}</a>
                        <!--save changes-->
                        <button class="btn btn-danger btn-sm"
                            data-url="{{ url('/estimates/'.$bill->bill_estimateid.'/edit-estimate') }}" data-type="form"
                            data-form-id="bill-form-container" data-ajax-type="post" id="billing-save-button">
                            {{ cleanLang(__('lang.save_changes')) }}
                        </button>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>

        <!--ADMIN ONLY NOTES-->
        @if(auth()->user()->is_team)
        @if(config('visibility.bill_mode') == 'viewing')
        <div class="card card-body invoice-wrapper box-shadow" id="invoice-wrapper">
            <h4 class="">{{ cleanLang(__('lang.notes')) }} <span class="align-middle text-themecontrast font-16"
                    data-toggle="tooltip" title="{{ cleanLang(__('lang.not_visisble_to_client')) }}"
                    data-placement="top"><i class="ti-info-alt"></i></span></h4>
            <div>{!! clean($bill->bill_notes) !!}</div>
        </div>
        @endif
        @if(config('visibility.bill_mode') == 'editing')
        <div class="card card-body invoice-wrapper box-shadow" id="invoice-wrapper">
            <h4 class="">{{ cleanLang(__('lang.notes')) }} <span class="align-middle text-themecontrast font-16"
                    data-toggle="tooltip" title="{{ cleanLang(__('lang.not_visisble_to_client')) }}"
                    data-placement="top"><i class="ti-info-alt"></i></span></h4>
            <div><textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="bill_notes"
                    id="bill_notes">{!! clean($bill->bill_notes) !!}</textarea></div>
        </div>
        @endif
        @endif

        <!--INVOICE LOGIC-->
        @if(config('visibility.bill_mode') == 'editing')
        @include('pages.bill.components.elements.logic')
        @endif

    </div>

    <!--ELEMENTS (invoice line item)-->
    @if(config('visibility.bill_mode') == 'editing')
    <table class="hidden" id="billing-line-template-plain">
        @include('pages.bill.components.elements.line-plain')
    </table>
    <table class="hidden" id="billing-line-template-time">
        @include('pages.bill.components.elements.line-time')
    </table>

    <!--MODALS-->
    @include('pages.bill.components.modals.items')
    @include('pages.bill.components.modals.expenses')
    @include('pages.bill.components.timebilling.modal')

    <!--[DYNAMIC INLINE SCRIPT] - Get lavarel objects and convert to javascript onject-->
    <script>
        $(document).ready(function () {
            @if(request()->segment(1) =="quotes")
                NXQUOTE.DATA.QUOTE = $.parseJSON('{!! $bill->json !!}');
                NXQUOTE.DOM.domState();
            @elseif(request()->segment(1) =="bols")
                NXBOL.DATA.BOL = $.parseJSON('{!! $bill->json !!}');
                NXBOL.DOM.domState();
            @else
                NXINVOICE.DATA.INVOICE = $.parseJSON('{!! $bill->json !!}');
                NXINVOICE.DOM.domState();
            @endif
        });
    </script>
    @endif