@foreach($quote-payments as $quote-payment)
<!--each row-->
<tr id="quote-payment_{{ $quote-payment->quote-payment_id }}">
    @if(config('visibility.quote-payments_col_checkboxes'))
    <td class="quote-payments_col_checkbox checkitem" id="quote-payments_col_checkbox_{{ $quote-payment->quote-payment_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-quote-payments-{{ $quote-payment->quote-payment_id }}"
                name="ids[{{ $quote-payment->quote-payment_id }}]"
                class="listcheckbox listcheckbox-quote-payments filled-in chk-col-light-blue"
                data-actions-container-class="quote-payments-checkbox-actions-container">
            <label for="listcheckbox-quote-payments-{{ $quote-payment->quote-payment_id }}"></label>
        </span>
    </td>
    @endif
    @if(config('visibility.quote-payments_col_id'))
    <td class="quote-payments_col_id" id="quote-payments_col_id_{{ $quote-payment->quote-payment_id }}"><a href="javascript:void(0)"
            class="show-modal-button js-ajax-ux-request" data-toggle="modal"
            data-url="{{ url( '/') }}/quote-payments/{{  $quote-payment->quote-payment_id }} " data-target="#plainModal"
            data-loading-target="plainModalBody" data-modal-title="">#{{ $quote-payment->quote-payment_id }}</a></td>
    @endif
    <td class="quote-payments_col_date" id="quote-payments_col_date_{{ $quote-payment->quote-payment_id }}">
        {{ runtimeDate($quote-payment->quote-payment_date) }}
    </td>
    @if(config('visibility.quote-payments_col_quoteid'))
    <td class="quote-payments_col_bill_quoteid" id="quote-payments_col_bill_quoteid_{{ $quote-payment->quote-payment_id }}">
        <a href="/quotes/{{ $quote-payment->quote-payment_quoteid }}">{{ runtimeQuoteIdFormat($quote-payment->bill_quoteid) }}</a>
    </td>
    @endif
    <td class="quote-payments_col_amount" id="quote-payments_col_amount_{{ $quote-payment->quote-payment_id }}">
        {{ runtimeMoneyFormat($quote-payment->quote-payment_amount) }}</td>
    @if(config('visibility.quote-payments_col_client'))
    <td class="quote-payments_col_client" id="quote-payments_col_client_{{ $quote-payment->quote-payment_id }}">
        <a
            href="/clients/{{ $quote-payment->quote-payment_clientid }}">{{ str_limit($quote-payment->client_company_name ?? '---', 20) }}</a>
    </td>
    @endif
    @if(config('visibility.quote-payments_col_project'))
    <td class="quote-payments_col_project" id="quote-payments_col_project_{{ $quote-payment->quote-payment_id }}">
        <a href="/projects/{{ $quote-payment->quote-payment_projectid }}">{{ str_limit($quote-payment->project_title ?? '---', 25) }}</a>
    </td>
    @endif
    <td class="quote-payments_col_transaction hidden" id="quote-payments_col_transaction_{{ $quote-payment->quote-payment_id }}">
        {{ str_limit($quote-payment->quote-payment_transaction_id ?? '---', 20) }}</td>
    @if(config('visibility.quote-payments_col_method'))
    <td class="quote-payments_col_method text-ucf" id="quote-payments_col_method_{{ $quote-payment->quote-payment_id }}">
        {{ $quote-payment->quote-payment_gateway }}
    </td>
    @endif
    @if(config('visibility.quote-payments_col_action'))
    <td class="quote-payments_col_action actions_column" id="quote-payments_col_action_{{ $quote-payment->quote-payment_id }}">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--edit-->
            @if(config('visibility.action_buttons_edit'))
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/quote-payments/'.$quote-payment->quote-payment_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_payment')) }}"
                data-action-url="{{ urlResource('/quote-payments/'.$quote-payment->quote-payment_id.'?ref=list') }}"
                data-action-method="PUT" data-action-ajax-class=""
                data-action-ajax-loading-target="quote-payments-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @endif
            <!--delete-->
            @if(config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_payment')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/quote-payments/{{ $quote-payment->quote-payment_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <a href="javascript:void(0)" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm show-modal-button js-ajax-ux-request"
                data-toggle="modal" data-url="{{ url( '/') }}/quote-payments/{{  $quote-payment->quote-payment_id }} "
                data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title="">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <!--action button-->
    </td>
    @endif
</tr>
<!--each row-->
@endforeach