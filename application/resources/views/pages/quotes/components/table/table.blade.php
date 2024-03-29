<div class="card count-{{ @count($quotes) }}" id="quotes-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper min-h-400">
            @if (@count($quotes) > 0)
            <table id="quotes-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                data-page-size="10" >
                <thead>
                    <tr>
                        @if(config('visibility.quotes_col_checkboxes'))
                        <th class="list-checkbox-wrapper">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-quotes" name="listcheckbox-quotes"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="quotes-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-quotes">
                                <label for="listcheckbox-quotes"></label>
                            </span>
                        </th>
                        @endif
                        <th class="quotes_col_id"><a class="js-ajax-ux-request js-list-sorting" id="sort_bill_quoteid"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/quotes?action=sort&orderby=bill_quoteid&sortorder=asc') }}">{{ cleanLang(__('lang.id')) }}<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="quotes_col_date"><a class="js-ajax-ux-request js-list-sorting"
                            id="sort_bill_date" href="javascript:void(0)"
                            data-url="{{ urlResource('/quotes?action=sort&orderby=bill_date&sortorder=asc') }}">{{ cleanLang(__('lang.date')) }}<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                    </th>
                        @if(config('visibility.quotes_col_client'))
                        <th class="quotes_col_company"><a class="js-ajax-ux-request js-list-sorting" id="sort_client"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/quotes?action=sort&orderby=client&sortorder=asc') }}">{{ cleanLang(__('lang.company_name')) }}<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a></th>
                        @endif
                        @if(config('visibility.quotes_col_project'))
                        <th class="quotes_col_project"><a class="js-ajax-ux-request js-list-sorting" id="sort_project"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/quotes?action=sort&orderby=project&sortorder=asc') }}">{{ cleanLang(__('lang.project')) }}<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif

                        <th class="quotes_col_amount"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_bill_final_amount" href="javascript:void(0)"
                                data-url="{{ urlResource('/quotes?action=sort&orderby=bill_final_amount&sortorder=asc') }}">{{ cleanLang(__('lang.amount')) }}<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @if(config('visibility.quotes_col_payments'))
                        <th class="quotes_col_payments"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_payments" href="javascript:void(0)"
                                data-url="{{ urlResource('/quotes?action=sort&orderby=payments&sortorder=asc') }}">{{ cleanLang(__('lang.payments')) }}<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @endif
                        <th class="quotes_col_balance hidden"><a class="js-ajax-ux-request js-list-sorting" id="sort_balance"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/quotes?action=sort&orderby=balance&sortorder=asc') }}">{{ cleanLang(__('lang.balance')) }}<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        {{-- <th class="quotes_col_status"><a class="js-ajax-ux-request js-list-sorting"
                                id="sort_bill_status" href="javascript:void(0)"
                                data-url="{{ urlResource('/quotes?action=sort&orderby=bill_status&sortorder=asc') }}">{{ cleanLang(__('lang.status')) }}<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th> --}}
                        <th class="quotes_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
                    </tr>
                </thead>
                <tbody id="quotes-td-container">
                    <!--ajax content here-->
                    @include('pages.quotes.components.table.ajax')
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
            @endif @if (@count($quotes) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>