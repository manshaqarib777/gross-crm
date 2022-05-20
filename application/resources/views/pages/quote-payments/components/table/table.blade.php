<div class="card count-{{ @count($quote-payments) }}" id="quote-payments-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            @if (@count($quote-payments) > 0)
            <table id="quote-payments-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                data-page-size="10">
                <thead>
                    <tr>
                        @if(config('visibility.quote-payments_col_checkboxes'))
                        <th class="list-checkbox-wrapper quote-payments_col_checkbox">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-quote-payments" name="listcheckbox-quote-payments"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="quote-payments-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-quote-payments">
                                <label for="listcheckbox-quote-payments"></label>
                            </span>
                        </th>
                        @endif
                        @if(config('visibility.quote-payments_col_id'))
                        <th class="quote-payments_col_id"><a class="js-ajax-ux-request js-list-sorting" id="sort_payment_id"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/quote-payments?action=sort&orderby=quote-payment_id&sortorder=asc') }}">{{ cleanLang(__('lang.id')) }}#<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif

                        <th class="quote-payments_col_date"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_payment_date" href="javascript:void(0)"
                                data-url="{{ urlResource('/quote-payments?action=sort&orderby=quote-payment_date&sortorder=asc') }}">{{ cleanLang(__('lang.date')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>

                        @if(config('visibility.quote-payments_col_quoteid'))
                        <th class="quote-payments_col_bill_quoteid"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_payment_quoteid" href="javascript:void(0)"
                                data-url="{{ urlResource('/quote-payments?action=sort&orderby=quote-payment_quoteid&sortorder=asc') }}">{{ cleanLang(__('lang.quote')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif

                        @if(config('visibility.quote-payments_col_quoteid'))
                        <th class="quote-payments_col_bill_quoteid"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_payment_quoteid" href="javascript:void(0)"
                                data-url="{{ urlResource('/quote-payments?action=sort&orderby=quote-payment_quoteid&sortorder=asc') }}">{{ cleanLang(__('lang.quote')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif

                        <th class="quote-payments_col_amount"><a class="js-ajax-ux-request js-list-sorting"
                            id="sort_payment_amount" href="javascript:void(0)"
                            data-url="{{ urlResource('/quote-payments?action=sort&orderby=quote-payment_amount&sortorder=asc') }}">{{ cleanLang(__('lang.amount')) }}<span
                                class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                    </th>
                        @if(config('visibility.quote-payments_col_client'))
                        <th class="quote-payments_col_client"><a class="js-ajax-ux-request js-list-sorting" id="sort_client"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/quote-payments?action=sort&orderby=client&sortorder=asc') }}">{{ cleanLang(__('lang.client')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif
                        @if(config('visibility.quote-payments_col_project'))
                        <th class="quote-payments_col_project"><a class="js-ajax-ux-request js-list-sorting" id="sort_project"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/quote-payments?action=sort&orderby=project&sortorder=asc') }}">{{ cleanLang(__('lang.project')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif
                        <th class="quote-payments_col_transaction hidden"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_payment_transaction_id" href="javascript:void(0)"
                                data-url="{{ urlResource('/quote-payments?action=sort&orderby=quote-payment_transaction_id&sortorder=asc') }}">{{ cleanLang(__('lang.transaction_id')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></a>
                        </th>

                        @if(config('visibility.quote-payments_col_method'))
                        <th class="quote-payments_col_method"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_payment_gateway" href="javascript:void(0)"
                                data-url="{{ urlResource('/quote-payments?action=sort&orderby=quote-payment_gateway&sortorder=asc') }}">{{ cleanLang(__('lang.method')) }}<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif

                        @if(config('visibility.quote-payments_col_action'))
                        <th class="quote-payments_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
                        @endif
                    </tr>
                </thead>
                <tbody id="quote-payments-td-container">
                    <!--ajax content here-->
                    @include('pages.quote-payments.components.table.ajax')
                    <!--ajax content here-->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="20">
                            <!--load more button-->
                            @include('misc.load-more-button')
                            <!--load more button-->
                        </td>
                    </tr>
                </tfoot>
            </table>
            @endif
            @if (@count($quote-payments) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>