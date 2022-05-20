<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-5 align-self-center text-right p-b-9 {{ $page['list_page_actions_size'] ?? '' }} {{ $page['list_page_container_class'] ?? '' }}"
    id="list-page-actions-container">
    <div id="list-page-actions">
        @if(auth()->user()->is_team && auth()->user()->role->role_quotes >= 2)
        <!--reminder-->
        @if(config('visibility.modules.reminders'))
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.reminder')) }}"
            id="reminders-panel-toggle-button"
            class="reminder-toggle-panel-button list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-reminder-panel ajax-request {{ $bill->reminder_status }}"
            data-url="{{ url('reminders/start?resource_type=quote&resource_id='.$bill->bill_quoteid) }}"
            data-loading-target="reminders-side-panel-body" data-progress-bar='hidden'
            data-target="reminders-side-panel" data-title="@lang('lang.my_reminder')">
            <i class="ti-alarm-clock"></i>
        </button>
        @endif
        @if($bill->bill_status == 'draft')
        <!--publish-->
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.publish_quote')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-info"
            href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.publish_quote')) }}"
            data-confirm-text="{{ cleanLang(__('lang.the_quote_will_be_sent_to_customer')) }}"
            data-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/publish') }}"
            id="quote-action-publish-quote"><i class="sl-icon-share-alt"></i></button>
        @endif
        {{-- <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.send_email')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-info"
            href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.send_email')) }}"
            data-confirm-text="{{ cleanLang(__('lang.confirm')) }}"
            data-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/resend') }}"
            id="quote-action-email-quote"><i class="ti-email"></i></button>
        <!--add quote-payment--> --}}
        <!--email quote-->
        <button type="button" title="{{ cleanLang(__('lang.send_email')) }}" id="quoteAddPaymentEmailButton"
            class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
            data-toggle="modal" data-target="#commonModal" data-modal-title="{{ cleanLang(__('lang.send_email')) }}"
            data-url="{{ url('/quote-payments/email?bill_quoteid='.$bill->bill_quoteid) }}"
            data-action-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/resend') }}"
            data-loading-target="actionsModalBody" data-action-method="GET">
            <i class="ti-email"></i>
        </button>
        <button type="button" title="{{ cleanLang(__('lang.add_a_quote-payment')) }}" id="quoteAddPaymentButton"
            class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
            data-toggle="modal" data-target="#commonModal" data-modal-title="{{ cleanLang(__('lang.add_a_quote-payment')) }}"
            data-url="{{ url('/quote-payments/create?bill_quoteid='.$bill->bill_quoteid) }}"
            data-action-url="{{ url('/quote-payments?ref=quote&source=page&bill_quoteid='.$bill->bill_quoteid) }}"
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
                    data-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/recurring-settings?source=page') }}"
                    data-loading-target="commonModalBody"
                    data-modal-title="{{ cleanLang(__('lang.recurring_settings')) }}"
                    data-action-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/recurring-settings?source=page') }}"
                    data-action-method="POST"
                    data-action-ajax-loading-target="quotes-td-container">{{ cleanLang(__('lang.recurring_settings')) }}</a>
                <a class="dropdown-item {{ runtimeVisibility('quote-view-child-quotes', $bill->bill_recurring) }}"
                    href="{{ url('quotes?filter_recurring_parent_id=').$bill->bill_quoteid }}"
                    id="quote-action-view-children">{{ cleanLang(__('lang.view_child_quotes')) }}</a>
                <a class="dropdown-item confirm-action-info display-block {{ runtimeVisibility('quote-stop-recurring', $bill->bill_recurring) }}"
                    href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.stop_recurring')) }}"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/stop-recurring') }}"
                    id="quote-action-stop-recurring">
                    {{ cleanLang(__('lang.stop_recurring')) }}</a>
            </div>
        </span>
        <!--clone-->
        <span class="dropdown">
            <button type="button" class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark 
                        actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                title="{{ cleanLang(__('lang.clone_quote')) }}" data-toggle="modal" data-target="#commonModal"
                data-modal-title="{{ cleanLang(__('lang.clone_quote')) }}"
                data-url="{{ url('/quotes/'.$bill->bill_quoteid.'/clone') }}"
                data-action-url="{{ url('/quotes/'.$bill->bill_quoteid.'/clone') }}"
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
                    href="{{ url('/quotes/'.$bill->bill_quoteid.'/edit-quote') }}">{{ cleanLang(__('lang.edit_quote')) }}</a>
                <!--attach project-->
                <a class="dropdown-item confirm-action-danger {{ runtimeVisibility('dettach-quote', $bill->bill_projectid)}}"
                    href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.detach_from_project')) }}"
                    id="bill-actions-dettach-project" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/detach-project') }}">
                    {{ cleanLang(__('lang.detach_from_project')) }}</a>
                <!--deattach project-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form {{ runtimeVisibility('attach-quote', $bill->bill_projectid)}}"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    id="bill-actions-attach-project" data-modal-title="{{ cleanLang(__('lang.attach_to_project')) }}"
                    data-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/attach-project?client_id='.$bill->bill_clientid) }}"
                    data-action-url="{{ urlResource('/quotes/'.$bill->bill_quoteid.'/attach-project') }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    {{ cleanLang(__('lang.attach_to_project')) }}</a>
            </div>

        </span>
        <!--delete-->
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.delete_quote')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-danger"
            data-confirm-title="{{ cleanLang(__('lang.delete_quote')) }}"
            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
            data-url="{{ url('/') }}/quotes/{{ $bill->bill_quoteid }}?source=page"><i
                class="sl-icon-trash"></i></button>

        @endif

        <!--reminder-->
        @if(auth()->user()->is_client)
        @if(config('visibility.modules.reminders'))
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.reminder')) }}"
            id="reminders-panel-toggle-button"
            class="reminder-toggle-panel-button list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-reminder-panel ajax-request {{ $bill->reminder_status }}"
            data-url="{{ url('reminders/start?resource_type=quote&resource_id='.$bill->bill_quoteid) }}"
            data-loading-target="reminders-side-panel-body" data-progress-bar='hidden'
            data-target="reminders-side-panel" data-title="@lang('lang.my_reminder')">
            <i class="ti-alarm-clock"></i>
        </button>
        @endif
        @endif

        <!--Download PDF-->
        <a data-toggle="tooltip" title="{{ cleanLang(__('lang.download')) }}" id="quoteDownloadButton"
            href="{{ url('/quotes/'.$bill->bill_quoteid.'/pdf') }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark" download>
            <i class="mdi mdi-download"></i>
        </a>

    </div>
</div>