@foreach($bol-payments as $bol-payment)
<!--each row-->
<tr id="bol-payment_{{ $bol-payment->bol-payment_id }}">
    @if(config('visibility.bol-payments_col_checkboxes'))
    <td class="bol-payments_col_checkbox checkitem" id="bol-payments_col_checkbox_{{ $bol-payment->bol-payment_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-bol-payments-{{ $bol-payment->bol-payment_id }}"
                name="ids[{{ $bol-payment->bol-payment_id }}]"
                class="listcheckbox listcheckbox-bol-payments filled-in chk-col-light-blue"
                data-actions-container-class="bol-payments-checkbox-actions-container">
            <label for="listcheckbox-bol-payments-{{ $bol-payment->bol-payment_id }}"></label>
        </span>
    </td>
    @endif
    @if(config('visibility.bol-payments_col_id'))
    <td class="bol-payments_col_id" id="bol-payments_col_id_{{ $bol-payment->bol-payment_id }}"><a href="javascript:void(0)"
            class="show-modal-button js-ajax-ux-request" data-toggle="modal"
            data-url="{{ url( '/') }}/bol-payments/{{  $bol-payment->bol-payment_id }} " data-target="#plainModal"
            data-loading-target="plainModalBody" data-modal-title="">#{{ $bol-payment->bol-payment_id }}</a></td>
    @endif
    <td class="bol-payments_col_date" id="bol-payments_col_date_{{ $bol-payment->bol-payment_id }}">
        {{ runtimeDate($bol-payment->bol-payment_date) }}
    </td>
    @if(config('visibility.bol-payments_col_bolid'))
    <td class="bol-payments_col_bill_bolid" id="bol-payments_col_bill_bolid_{{ $bol-payment->bol-payment_id }}">
        <a href="/bols/{{ $bol-payment->bol-payment_bolid }}">{{ runtimeBolIdFormat($bol-payment->bill_bolid) }}</a>
    </td>
    @endif
    <td class="bol-payments_col_amount" id="bol-payments_col_amount_{{ $bol-payment->bol-payment_id }}">
        {{ runtimeMoneyFormat($bol-payment->bol-payment_amount) }}</td>
    @if(config('visibility.bol-payments_col_client'))
    <td class="bol-payments_col_client" id="bol-payments_col_client_{{ $bol-payment->bol-payment_id }}">
        <a
            href="/clients/{{ $bol-payment->bol-payment_clientid }}">{{ str_limit($bol-payment->client_company_name ?? '---', 20) }}</a>
    </td>
    @endif
    @if(config('visibility.bol-payments_col_project'))
    <td class="bol-payments_col_project" id="bol-payments_col_project_{{ $bol-payment->bol-payment_id }}">
        <a href="/projects/{{ $bol-payment->bol-payment_projectid }}">{{ str_limit($bol-payment->project_title ?? '---', 25) }}</a>
    </td>
    @endif
    <td class="bol-payments_col_transaction hidden" id="bol-payments_col_transaction_{{ $bol-payment->bol-payment_id }}">
        {{ str_limit($bol-payment->bol-payment_transaction_id ?? '---', 20) }}</td>
    @if(config('visibility.bol-payments_col_method'))
    <td class="bol-payments_col_method text-ucf" id="bol-payments_col_method_{{ $bol-payment->bol-payment_id }}">
        {{ $bol-payment->bol-payment_gateway }}
    </td>
    @endif
    @if(config('visibility.bol-payments_col_action'))
    <td class="bol-payments_col_action actions_column" id="bol-payments_col_action_{{ $bol-payment->bol-payment_id }}">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--edit-->
            @if(config('visibility.action_buttons_edit'))
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/bol-payments/'.$bol-payment->bol-payment_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_payment')) }}"
                data-action-url="{{ urlResource('/bol-payments/'.$bol-payment->bol-payment_id.'?ref=list') }}"
                data-action-method="PUT" data-action-ajax-class=""
                data-action-ajax-loading-target="bol-payments-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @endif
            <!--delete-->
            @if(config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_payment')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/bol-payments/{{ $bol-payment->bol-payment_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <a href="javascript:void(0)" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm show-modal-button js-ajax-ux-request"
                data-toggle="modal" data-url="{{ url( '/') }}/bol-payments/{{  $bol-payment->bol-payment_id }} "
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