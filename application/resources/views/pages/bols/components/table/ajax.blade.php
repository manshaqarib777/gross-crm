@foreach($bols as $bol)
<!--each row-->
<tr id="bol_{{ $bol->bill_bolid  }}">
    @if(config('visibility.bols_col_checkboxes'))
    <td class="bols_col_checkbox checkitem" id="bols_col_checkbox_{{ $bol->bill_bolid }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-bols-{{ $bol->bill_bolid }}"
                name="ids[{{ $bol->bill_bolid }}]"
                class="listcheckbox listcheckbox-bols filled-in chk-col-light-blue"
                data-actions-container-class="bols-checkbox-actions-container">
            <label for="listcheckbox-bols-{{ $bol->bill_bolid }}"></label>
        </span>
    </td>
    @endif
    <td class="bols_col_id" id="bols_col_id_{{ $bol->bill_bolid }}">
        <a href="/bols/{{ $bol->bill_bolid }}">
            {{ $bol->formatted_bill_bolid }} </a>
        <!--recurring-->
        @if(auth()->user()->is_team && $bol->bill_recurring == 'yes')
        <span class="sl-icon-refresh text-danger p-l-5" data-toggle="tooltip" title="@lang('lang.recurring_bol')"></span>
        @endif
        <!--child bol-->
        @if(auth()->user()->is_team && $bol->bill_recurring_child == 'yes')
        <a href="{{ url('bols/'.$bol->bill_recurring_parent_id) }}">
            <i class="ti-back-right text-success p-l-5" data-toggle="tooltip" data-html="true"
                title="{{ cleanLang(__('lang.bol_automatically_created_from_recurring')) }} <br>(#{{ runtimeBolIdFormat($bol->bill_recurring_parent_id) }})"></i>
        </a>
        @endif
    </td>
    <td class="bols_col_date" id="bols_col_date_{{ $bol->bill_bolid }}">
        {{ runtimeDate($bol->bill_date) }}
    </td>
    @if(config('visibility.bols_col_client'))
    <td class="bols_col_company" id="bols_col_company_{{ $bol->bill_bolid }}">
        <a href="/clients/{{ $bol->bill_clientid }}">{{ str_limit($bol->client_company_name ?? '---', 12) }}</a>
    </td>
    @endif
    @if(config('visibility.bols_col_project'))
    <td class="bols_col_project" id="bols_col_project_{{ $bol->bill_bolid }}">
        <a href="/projects/{{ $bol->bill_projectid }}">{{ str_limit($bol->project_title ?? '---', 12) }}</a>
    </td>
    @endif

    <td class="bols_col_amount" id="bols_col_amount_{{ $bol->bill_bolid }}">
        {{ runtimeMoneyFormat($bol->bill_final_amount) }}</td>
    @if(config('visibility.bols_col_payments'))
    <td class="bols_col_payments" id="bols_col_payments_{{ $bol->bill_bolid }}">
        <a
            href="{{ url('payments?filter_payment_bolid='.$bol->bill_bolid) }}">{{ runtimeMoneyFormat($bol->sum_payments) }}</a>
    </td>
    @endif
    <td class="bols_col_balance hidden" id="bols_col_balance_{{ $bol->bill_bolid }}">
        {{ runtimeMoneyFormat($bol->bol_balance) }}
    </td>
    <td class="bols_col_status" id="bols_col_status_{{ $bol->bill_bolid }}">

        <span class="label {{ runtimeBolStatusColors($bol->bill_status, 'label') }}">{{
            runtimeLang($bol->bill_status) }}</span>

        @if(config('system.settings_estimates_show_view_status') == 'yes' && auth()->user()->is_team &&
        $bol->bill_status != 'draft' && $bol->bill_status != 'paid')
        <!--estimate not viewed-->
        @if($bol->bill_viewed_by_client == 'no')
        <span class="label label-icons label-icons-default" data-toggle="tooltip" data-placement="top"
            title="@lang('lang.client_has_not_opened')"><i class="sl-icon-eye"></i></span>
        @endif
        <!--estimate viewed-->
        @if($bol->bill_viewed_by_client == 'yes')
        <span class="label label-icons label-icons-info" data-toggle="tooltip" data-placement="top"
            title="@lang('lang.client_has_opened')"><i class="sl-icon-eye"></i></span>
        @endif
        @endif

    </td>
    <td class="bols_col_action actions_column" id="bols_col_action_{{ $bol->bill_bolid }}">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">

            <!--client options-->
            @if(auth()->user()->is_client)
            <a title="{{ cleanLang(__('lang.download')) }}" title="{{ cleanLang(__('lang.download')) }}"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-warning btn-circle btn-sm"
                href="{{ url('/bols/'.$bol->bill_bolid.'/pdf') }}" download>
                <i class="ti-import"></i></a>
            @endif
            <!--delete-->
            @if(config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_bol')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/bols/{{ $bol->bill_bolid }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <!--edit-->
            @if(config('visibility.action_buttons_edit'))
            <a href="/bols/{{ $bol->bill_bolid }}/edit-bol" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="sl-icon-note"></i>
            </a>
            @endif
            <a href="/bols/{{ $bol->bill_bolid }}" title="{{ cleanLang(__('lang.view')) }}"
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
                        data-url="{{ urlResource('/bols/'.$bol->bill_bolid.'/edit') }}"
                        data-loading-target="commonModalBody"
                        data-modal-title="{{ cleanLang(__('lang.edit_bol')) }}"
                        data-action-url="{{ urlResource('/bols/'.$bol->bill_bolid.'?ref=list') }}"
                        data-action-method="PUT" data-action-ajax-class=""
                        data-action-ajax-loading-target="bols-td-container">
                        {{ cleanLang(__('lang.quick_edit')) }}
                    </a>
                    <!--email to client-->
                    <a class="dropdown-item confirm-action-info" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.email_to_client')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-url="{{ url('/bols') }}/{{ $bol->bill_bolid }}/resend?ref=list">
                        {{ cleanLang(__('lang.email_to_client')) }}</a>
                    <!--add payment-->
                    @if(auth()->user()->role->role_bols > 1)
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                        href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                        data-modal-title="{{ cleanLang(__('lang.add_new_payment')) }}"
                        data-url="{{ url('/payments/create?bill_bolid='.$bol->bill_bolid) }}"
                        data-action-url="{{ urlResource('/payments?ref=bol&source=list&bill_bolid='.$bol->bill_bolid) }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.add_new_payment')) }}</a>
                    @endif
                    <!--clone bol-->
                    @if(auth()->user()->role->role_bols > 1)
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                        href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                        data-modal-title="{{ cleanLang(__('lang.clone_bol')) }}"
                        data-url="{{ url('/bols/'.$bol->bill_bolid.'/clone') }}"
                        data-action-url="{{ url('/bols/'.$bol->bill_bolid.'/clone') }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.clone_bol')) }}</a>
                    @endif
                    <!--change category-->
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title="{{ cleanLang(__('lang.change_category')) }}"
                        data-url="{{ url('/bols/change-category') }}"
                        data-action-url="{{ urlResource('/bols/change-category?id='.$bol->bill_bolid) }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.change_category')) }}</a>
                    <!--attach project -->
                    @if(!is_numeric($bol->bill_projectid))
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title=" {{ cleanLang(__('lang.attach_to_project')) }}"
                        data-url="{{ urlResource('/bols/'.$bol->bill_bolid.'/attach-project?client_id='.$bol->bill_clientid) }}"
                        data-action-url="{{ urlResource('/bols/'.$bol->bill_bolid.'/attach-project') }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.attach_to_project')) }}</a>
                    @endif
                    <!--dettach project -->
                    @if(is_numeric($bol->bill_projectid))
                    <a class="dropdown-item confirm-action-danger" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.detach_from_project')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-url="{{ urlResource('/bols/'.$bol->bill_bolid.'/detach-project') }}">
                        {{ cleanLang(__('lang.detach_from_project')) }}</a>
                    @endif
                    <!--recurring settings-->
                    <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                        data-url="{{ urlResource('/bols/'.$bol->bill_bolid.'/recurring-settings?source=page') }}"
                        data-loading-target="commonModalBody"
                        data-modal-title="{{ cleanLang(__('lang.recurring_settings')) }}"
                        data-action-url="{{ urlResource('/bols/'.$bol->bill_bolid.'/recurring-settings?source=page') }}"
                        data-action-method="POST"
                        data-action-ajax-loading-target="bols-td-container">{{ cleanLang(__('lang.recurring_settings')) }}</a>
                    <!--stop recurring -->
                    @if($bol->bill_recurring == 'yes')
                    <a class="dropdown-item confirm-action-info" href="javascript:void(0)"
                        data-confirm-title="{{ cleanLang(__('lang.stop_recurring')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-url="{{ urlResource('/bols/'.$bol->bill_bolid.'/stop-recurring') }}">
                        {{ cleanLang(__('lang.stop_recurring')) }}</a>
                    @endif
                    @endif
                    <a class="dropdown-item"
                        href="{{ url('payments?filter_payment_bolid='.$bol->bill_bolid) }}">
                        {{ cleanLang(__('lang.view_payments')) }}</a>
                    <a class="dropdown-item" href="{{ url('/bols/'.$bol->bill_bolid.'/pdf') }}" download>
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