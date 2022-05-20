<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-5 align-self-center text-right p-b-9 {{ $page['list_page_actions_size'] ?? '' }} {{ $page['list_page_container_class'] ?? '' }}"
    id="list-page-actions-container">
    <div id="list-page-actions">
        @if(auth()->user()->is_team && auth()->user()->role->role_bols >= 2)
        <!--reminder-->
        @if(config('visibility.modules.reminders'))
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.reminder')) }}"
            id="reminders-panel-toggle-button"
            class="reminder-toggle-panel-button list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-reminder-panel ajax-request {{ $bill->reminder_status }}"
            data-url="{{ url('reminders/start?resource_type=bol&resource_id='.$bill->bill_bolid) }}"
            data-loading-target="reminders-side-panel-body" data-progress-bar='hidden'
            data-target="reminders-side-panel" data-title="@lang('lang.my_reminder')">
            <i class="ti-alarm-clock"></i>
        </button>
        @endif
        @if($bill->bill_status == 'draft')
        <!--publish-->
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.publish_bol')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-info"
            href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.publish_bol')) }}"
            data-confirm-text="{{ cleanLang(__('lang.the_bol_will_be_sent_to_customer')) }}"
            data-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/publish') }}"
            id="bol-action-publish-bol"><i class="sl-icon-share-alt"></i></button>
        @endif
        {{-- <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.send_email')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-info"
            href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.send_email')) }}"
            data-confirm-text="{{ cleanLang(__('lang.confirm')) }}"
            data-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/resend') }}"
            id="bol-action-email-bol"><i class="ti-email"></i></button>
        <!--add bol-payment--> --}}
        <!--email bol-->
        <button type="button" title="{{ cleanLang(__('lang.send_email')) }}" id="bolAddPaymentEmailButton"
            class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
            data-toggle="modal" data-target="#commonModal" data-modal-title="{{ cleanLang(__('lang.send_email')) }}"
            data-url="{{ url('/bol-payments/email?bill_bolid='.$bill->bill_bolid) }}"
            data-action-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/resend') }}"
            data-loading-target="actionsModalBody" data-action-method="GET">
            <i class="ti-email"></i>
        </button>
        <button type="button" title="{{ cleanLang(__('lang.add_a_bol-payment')) }}" id="bolAddPaymentButton"
            class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
            data-toggle="modal" data-target="#commonModal" data-modal-title="{{ cleanLang(__('lang.add_a_bol-payment')) }}"
            data-url="{{ url('/bol-payments/create?bill_bolid='.$bill->bill_bolid) }}"
            data-action-url="{{ url('/bol-payments?ref=bol&source=page&bill_bolid='.$bill->bill_bolid) }}"
            data-loading-target="actionsModalBody" data-action-method="POST">
            <i class="ti-credit-card"></i>
        </button>
        <!--recurring options-->
        <span class="dropdown">
            <button type="button" data-toggle="dropdown" title="{{ cleanLang(__('lang.recurring_options')) }}"
                aria-haspopup="true" aria-expanded="false"
                class="data-toggle-tooltip  list-actions-button btn btn-page-actions waves-effect waves-dark">
                <i class="sl-icon-refresh"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <!--recurring settings-->
                <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                    data-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/recurring-settings?source=page') }}"
                    data-loading-target="commonModalBody"
                    data-modal-title="{{ cleanLang(__('lang.recurring_settings')) }}"
                    data-action-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/recurring-settings?source=page') }}"
                    data-action-method="POST"
                    data-action-ajax-loading-target="bols-td-container">{{ cleanLang(__('lang.recurring_settings')) }}</a>
                <a class="dropdown-item {{ runtimeVisibility('bol-view-child-bols', $bill->bill_recurring) }}"
                    href="{{ url('bols?filter_recurring_parent_id=').$bill->bill_bolid }}"
                    id="bol-action-view-children">{{ cleanLang(__('lang.view_child_bols')) }}</a>
                <a class="dropdown-item confirm-action-info display-block {{ runtimeVisibility('bol-stop-recurring', $bill->bill_recurring) }}"
                    href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.stop_recurring')) }}"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/stop-recurring') }}"
                    id="bol-action-stop-recurring">
                    {{ cleanLang(__('lang.stop_recurring')) }}</a>
            </div>
        </span>
        <!--clone-->
        <span class="dropdown">
            <button type="button" class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark 
                        actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                title="{{ cleanLang(__('lang.clone_bol')) }}" data-toggle="modal" data-target="#commonModal"
                data-modal-title="{{ cleanLang(__('lang.clone_bol')) }}"
                data-url="{{ url('/bols/'.$bill->bill_bolid.'/clone') }}"
                data-action-url="{{ url('/bols/'.$bill->bill_bolid.'/clone') }}"
                data-loading-target="actionsModalBody" data-action-method="POST">
                <i class=" mdi mdi-content-copy"></i>
            </button>
        </span>
        <!--edit-->
        <span class="dropdown">
            <button type="button" data-toggle="dropdown" title="{{ cleanLang(__('lang.edit')) }}" aria-haspopup="true"
                aria-expanded="false"
                class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark">
                <i class="sl-icon-note"></i>
            </button>

            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <a class="dropdown-item"
                    href="{{ url('/bols/'.$bill->bill_bolid.'/edit-bol') }}">{{ cleanLang(__('lang.edit_bol')) }}</a>
                <!--attach project-->
                <a class="dropdown-item confirm-action-danger {{ runtimeVisibility('dettach-bol', $bill->bill_projectid)}}"
                    href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.detach_from_project')) }}"
                    id="bill-actions-dettach-project" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/detach-project') }}">
                    {{ cleanLang(__('lang.detach_from_project')) }}</a>
                <!--deattach project-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form {{ runtimeVisibility('attach-bol', $bill->bill_projectid)}}"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    id="bill-actions-attach-project" data-modal-title="{{ cleanLang(__('lang.attach_to_project')) }}"
                    data-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/attach-project?client_id='.$bill->bill_clientid) }}"
                    data-action-url="{{ urlResource('/bols/'.$bill->bill_bolid.'/attach-project') }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    {{ cleanLang(__('lang.attach_to_project')) }}</a>
            </div>

        </span>
        <!--delete-->
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.delete_bol')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-danger"
            data-confirm-title="{{ cleanLang(__('lang.delete_bol')) }}"
            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
            data-url="{{ url('/') }}/bols/{{ $bill->bill_bolid }}?source=page"><i
                class="sl-icon-trash"></i></button>

        @endif

        <!--reminder-->
        @if(auth()->user()->is_client)
        @if(config('visibility.modules.reminders'))
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.reminder')) }}"
            id="reminders-panel-toggle-button"
            class="reminder-toggle-panel-button list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-reminder-panel ajax-request {{ $bill->reminder_status }}"
            data-url="{{ url('reminders/start?resource_type=bol&resource_id='.$bill->bill_bolid) }}"
            data-loading-target="reminders-side-panel-body" data-progress-bar='hidden'
            data-target="reminders-side-panel" data-title="@lang('lang.my_reminder')">
            <i class="ti-alarm-clock"></i>
        </button>
        @endif
        @endif

        <!--Download PDF-->
        <a data-toggle="tooltip" title="{{ cleanLang(__('lang.download')) }}" id="bolDownloadButton"
            href="{{ url('/bols/'.$bill->bill_bolid.'/pdf') }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark" download>
            <i class="mdi mdi-download"></i>
        </a>

    </div>
</div>