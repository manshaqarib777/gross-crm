        <!--HEADER-->
        <div>
            <span class="pull-left">
                <h3><b>{{ cleanLang(__('lang.quote')) }}</b>
                    <!--recurring icon-->
                    @if(auth()->user()->is_team)
                    <i class="sl-icon-refresh text-danger cursor-pointer {{ runtimeVisibility('quote-recurring-icon', $bill->bill_recurring) }}"
                        data-toggle="tooltip" id="quote-recurring-icon" title="{{ cleanLang(__('lang.recurring_quote')) }}"></i>
                    <!--child quote-->
                    @if($bill->bill_recurring_child == 'yes')
                    <a href="{{ url('quotes/'.$bill->bill_recurring_parent_id) }}">
                        <i class="ti-back-right text-success" data-toggle="tooltip" data-html="true"
                            title="{{ cleanLang(__('lang.quote_automatically_created_from_recurring')) }} <br>(#{{ runtimeQuoteIdFormat($bill->bill_recurring_parent_id) }})"></i>
                    </a>
                    @endif
                    @endif
                </h3>
                <span>
                    <h5>#{{ $bill->formatted_bill_quoteid }}</h5>
                </span>
            </span>
            <!--status-->
            <span class="pull-right text-align-right">
                <!--draft-->
                <span class="js-quote-statuses {{ runtimeQuoteStatus('draft', $bill->bill_status) }}"
                    id="quote-status-draft">
                    <h1 class="text-uppercase {{ runtimeQuoteStatusColors('draft', 'text') }} muted">{{ cleanLang(__('lang.draft')) }}</h1>
                </span>
                <!--due-->
                <span class="js-quote-statuses {{ runtimeQuoteStatus('due', $bill->bill_status) }}"
                    id="quote-status-due">
                    <h1 class="text-uppercase {{ runtimeQuoteStatusColors('due', 'text') }}">{{ cleanLang(__('lang.due')) }}</h1>
                </span>
                <!--overdue-->
                <span class="js-quote-statuses {{ runtimeQuoteStatus('overdue', $bill->bill_status) }}"
                    id="quote-status-overdue">
                    <h1 class="text-uppercase {{ runtimeQuoteStatusColors('overdue', 'text') }}">{{ cleanLang(__('lang.overdue')) }}</h1>
                </span>
                <!--paid-->
                <span class="js-quote-statuses {{ runtimeQuoteStatus('paid', $bill->bill_status) }}"
                    id="quote-status-paid">
                    <h1 class="text-uppercase {{ runtimeQuoteStatusColors('paid', 'text') }}">{{ cleanLang(__('lang.paid')) }}</h1>
                </span>
                @if(config('system.settings_estimates_show_view_status') == 'yes' && auth()->user()->is_team && $bill->bill_status != 'draft' && $bill->bill_status != 'paid')
                @if($bill->bill_viewed_by_client == 'no')
                <span>
                    <span class="label label-light-inverse text-lc font-normal">@lang('lang.client_has_not_opened')</span>
                </span>
                @endif
                @if($bill->bill_viewed_by_client == 'yes')
                <span>
                    <span class="label label label-lighter-info text-lc font-normal">@lang('lang.client_has_opened')</span>
                </span>
                @endif
                @endif
            </span>
        </div>