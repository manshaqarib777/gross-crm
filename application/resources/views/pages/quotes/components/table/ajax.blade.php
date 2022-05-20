@foreach($quotes as $quote)
<!--each row-->
<tr id="quote_{{ $quote->bill_quoteid  }}">
    @if(config('visibility.quotes_col_checkboxes'))
    <td class="quotes_col_checkbox checkitem" id="quotes_col_checkbox_{{ $quote->bill_quoteid }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-quotes-{{ $quote->bill_quoteid }}"
                name="ids[{{ $quote->bill_quoteid }}]"
                class="listcheckbox listcheckbox-quotes filled-in chk-col-light-blue"
                data-actions-container-class="quotes-checkbox-actions-container">
            <label for="listcheckbox-quotes-{{ $quote->bill_quoteid }}"></label>
        </span>
    </td>
    @endif
    <td class="quotes_col_id" id="quotes_col_id_{{ $quote->bill_quoteid }}">
        <a href="/quotes/{{ $quote->bill_quoteid }}">
            {{ $quote->formatted_bill_quoteid }} </a>
        <!--recurring-->
        @if(auth()->user()->is_team && $quote->bill_recurring == 'yes')
        <span class="sl-icon-refresh text-danger p-l-5" data-toggle="tooltip" title="@lang('lang.recurring_quote')"></span>
        @endif
        <!--child quote-->
        @if(auth()->user()->is_team && $quote->bill_recurring_child == 'yes')
        <a href="{{ url('quotes/'.$quote->bill_recurring_parent_id) }}">
            <i class="ti-back-right text-success p-l-5" data-toggle="tooltip" data-html="true"
                title="{{ cleanLang(__('lang.quote_automatically_created_from_recurring')) }} <br>(#{{ runtimeQuoteIdFormat($quote->bill_recurring_parent_id) }})"></i>
        </a>
        @endif
    </td>
    <td class="quotes_col_date" id="quotes_col_date_{{ $quote->bill_quoteid }}">
        {{ runtimeDate($quote->bill_date) }}
    </td>
    @if(config('visibility.quotes_col_client'))
    <td class="quotes_col_company" id="quotes_col_company_{{ $quote->bill_quoteid }}">
        <a href="/clients/{{ $quote->bill_clientid }}">{{ str_limit($quote->client_company_name ?? '---', 12) }}</a>
    </td>
    @endif
    @if(config('visibility.quotes_col_project'))
    <td class="quotes_col_project" id="quotes_col_project_{{ $quote->bill_quoteid }}">
        <a href="/projects/{{ $quote->bill_projectid }}">{{ str_limit($quote->project_title ?? '---', 12) }}</a>
    </td>
    @endif

    <td class="quotes_col_amount" id="quotes_col_amount_{{ $quote->bill_quoteid }}">
        {{ runtimeMoneyFormat($quote->bill_final_amount) }}</td>
    @if(config('visibility.quotes_col_payments'))
    <td class="quotes_col_payments" id="quotes_col_payments_{{ $quote->bill_quoteid }}">
        <a
            href="{{ url('payments?filter_payment_quoteid='.$quote->bill_quoteid) }}">{{ runtimeMoneyFormat($quote->sum_payments) }}</a>
    </td>
    @endif
    <td class="quotes_col_balance hidden" id="quotes_col_balance_{{ $quote->bill_quoteid }}">
        {{ runtimeMoneyFormat($quote->quote_balance) }}
    </td>
    <td class="quotes_col_status" id="quotes_col_status_{{ $quote->bill_quoteid }}">

        <span class="label {{ runtimeQuoteStatusColors($quote->bill_status, 'label') }}">{{
            runtimeLang($quote->bill_status) }}</span>

        @if(config('system.settings_estimates_show_view_status') == 'yes' && auth()->user()->is_team &&
        $quote->bill_status != 'draft' && $quote->bill_status != 'paid')
        <!--estimate not viewed-->
        @if($quote->bill_viewed_by_client == 'no')
        <span class="label label-icons label-icons-default" data-toggle="tooltip" data-placement="top"
            title="@lang('lang.client_has_not_opened')"><i class="sl-icon-eye"></i></span>
        @endif
        <!--estimate viewed-->
        @if($quote->bill_viewed_by_client == 'yes')
        <span class="label label-icons label-icons-info" data-toggle="tooltip" data-placement="top"
            title="@lang('lang.client_has_opened')"><i class="sl-icon-eye"></i></span>
        @endif
        @endif

    </td>
    <td class="quotes_col_action actions_column" id="quotes_col_action_{{ $quote->bill_quoteid }}">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">

            <!--client options-->
            @if(auth()->user()->is_client)
            <a title="{{ cleanLang(__('lang.download')) }}" title="{{ cleanLang(__('lang.download')) }}"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-warning btn-circle btn-sm"
                href="{{ url('/quotes/'.$quote->bill_quoteid.'/pdf') }}" download>
                <i class="ti-import"></i></a>
            @endif
            <!--delete-->
            @if(config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_quote')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/quotes/{{ $quote->bill_quoteid }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <!--edit-->
            @if(config('visibility.action_buttons_edit'))
            <a href="/quotes/{{ $quote->bill_quoteid }}/edit-quote" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="sl-icon-note"></i>
            </a>
            @endif
            <a href="/quotes/{{ $quote->bill_quoteid }}" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>

            <!--more button (team)-->
            @if(auth()->user()->is_team)
            <span class="list-table-action dropdown font-size-inherit">
                <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" title="{{ cleanLang(__('lang.more')) }}"
                    class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm">
                    <i class="ti-more"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="listTableAction">
                    @if(config('visibility.action_buttons_edit'))
                    <!--quick edit-->
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                        data-toggle="modal" data-target="#commonModal"
                        data-url="{{ urlResource('/quotes/'.$quote->bill_quoteid.'/edit') }}"
                        data-loading-target="commonModalBody"
                        data-modal-title="{{ cleanLang(__('lang.edit_quote')) }}"
                        data-action-url="{{ urlResource('/quotes/'.$quote->bill_quoteid.'?ref=list') }}"
                        data-action-method="PUT" data-action-ajax-class=""
                        data-action-ajax-loading-target="quotes-td-container">
                        {{ cleanLang(__('lang.quick_edit')) }}
                    </a>
                    <!--email to client-->
                    <a class="dropdown-item confirm-action-info" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.email_to_client')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-url="{{ url('/quotes') }}/{{ $quote->bill_quoteid }}/resend?ref=list">
                        {{ cleanLang(__('lang.email_to_client')) }}</a>
                    <!--add payment-->
                    @if(auth()->user()->role->role_quotes > 1)
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                        href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                        data-modal-title="{{ cleanLang(__('lang.add_new_payment')) }}"
                        data-url="{{ url('/payments/create?bill_quoteid='.$quote->bill_quoteid) }}"
                        data-action-url="{{ urlResource('/payments?ref=quote&source=list&bill_quoteid='.$quote->bill_quoteid) }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.add_new_payment')) }}</a>
                    @endif
                    <!--clone quote-->
                    @if(auth()->user()->role->role_quotes > 1)
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                        href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                        data-modal-title="{{ cleanLang(__('lang.clone_quote')) }}"
                        data-url="{{ url('/quotes/'.$quote->bill_quoteid.'/clone') }}"
                        data-action-url="{{ url('/quotes/'.$quote->bill_quoteid.'/clone') }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.clone_quote')) }}</a>
                    @endif
                    <!--change category-->
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title="{{ cleanLang(__('lang.change_category')) }}"
                        data-url="{{ url('/quotes/change-category') }}"
                        data-action-url="{{ urlResource('/quotes/change-category?id='.$quote->bill_quoteid) }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.change_category')) }}</a>
                    <!--attach project -->
                    @if(!is_numeric($quote->bill_projectid))
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title=" {{ cleanLang(__('lang.attach_to_project')) }}"
                        data-url="{{ urlResource('/quotes/'.$quote->bill_quoteid.'/attach-project?client_id='.$quote->bill_clientid) }}"
                        data-action-url="{{ urlResource('/quotes/'.$quote->bill_quoteid.'/attach-project') }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.attach_to_project')) }}</a>
                    @endif
                    <!--dettach project -->
                    @if(is_numeric($quote->bill_projectid))
                    <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.detach_from_project')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-url="{{ urlResource('/quotes/'.$quote->bill_quoteid.'/detach-project') }}">
                        {{ cleanLang(__('lang.detach_from_project')) }}</a>
                    @endif
                    <!--recurring settings-->
                    <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                        data-url="{{ urlResource('/quotes/'.$quote->bill_quoteid.'/recurring-settings?source=page') }}"
                        data-loading-target="commonModalBody"
                        data-modal-title="{{ cleanLang(__('lang.recurring_settings')) }}"
                        data-action-url="{{ urlResource('/quotes/'.$quote->bill_quoteid.'/recurring-settings?source=page') }}"
                        data-action-method="POST"
                        data-action-ajax-loading-target="quotes-td-container">{{ cleanLang(__('lang.recurring_settings')) }}</a>
                    <!--stop recurring -->
                    @if($quote->bill_recurring == 'yes')
                    <a class="dropdown-item confirm-action-info" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.stop_recurring')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-url="{{ urlResource('/quotes/'.$quote->bill_quoteid.'/stop-recurring') }}">
                        {{ cleanLang(__('lang.stop_recurring')) }}</a>
                    @endif
                    @endif
                    <a class="dropdown-item"
                        href="{{ url('payments?filter_payment_quoteid='.$quote->bill_quoteid) }}">
                        {{ cleanLang(__('lang.view_payments')) }}</a>
                    <a class="dropdown-item" href="{{ url('/quotes/'.$quote->bill_quoteid.'/pdf') }}" download>
                        {{ cleanLang(__('lang.download')) }}</a>
                </div>
            </span>
            @endif
            <!--more button-->
        </span>
        <!--action button-->

    </td>
</tr>
@endforeach
<!--each row-->