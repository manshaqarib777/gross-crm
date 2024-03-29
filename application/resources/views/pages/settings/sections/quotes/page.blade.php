@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form" id="settingsFormQuotes">
    <!--form text tem-->
    <div class="form-group row">
        <label class="col-12 control-label col-form-label">{{ cleanLang(__('lang.quote_prefix')) }}</label>
        <div class="col-12">
            <input type="text" class="form-control form-control-sm" id="settings_quotes_prefix"
                name="settings_quotes_prefix" value="{{ $settings->settings_quotes_prefix ?? '' }}">
        </div>
    </div>

    <!--form text tem-->
    <div class="form-group row">
        <label
            class="col-12 control-label col-form-label font-16">{{ cleanLang(__('lang.bill_recurring_grace_period')) }}
            <span class="align-middle text-themecontrast" data-toggle="tooltip"
                title="{{ cleanLang(__('lang.bill_recurring_grace_period_info')) }}" data-placement="top"><i
                    class="ti-info-alt"></i></span></label>
        <div class="col-12">
            <input type="number" class="form-control form-control-sm" id="settings_quotes_recurring_grace_period"
                name="settings_quotes_recurring_grace_period"
                value="{{ $settings->settings_quotes_recurring_grace_period ?? '' }}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.terms_and_conditions')) }}</label>
        <div class="col-12 p-t-5">
            <textarea class="form-control form-control-sm tinymce-textarea" rows="5"
                name="settings_quotes_default_terms_conditions" id="settings_quotes_default_terms_conditions">
                    {{ $settings->settings_quotes_default_terms_conditions ?? '' }}
                </textarea>
        </div>
    </div>


    <!--form checkbox item-->
    <div class="form-group form-group-checkbox row">
        <div class="col-12 p-t-5">
            <input type="checkbox" id="settings_quotes_show_view_status"
                name="settings_quotes_show_view_status" class="filled-in chk-col-light-blue"
                {{ runtimePrechecked($settings['settings_quotes_show_view_status'] ?? '') }}>
            <label for="settings_quotes_show_view_status">{{ cleanLang(__('lang.show_if_client_has_opened')) }}</label>
        </div>
    </div>

    <div>
        <!--settings documentation help-->
        <a href="https://growcrm.io/documentation/quote-settings/" target="_blank"
            class="btn btn-sm btn-info help-documentation"><i class="ti-info-alt"></i>
            {{ cleanLang(__('lang.help_documentation')) }}</a>
    </div>

    <!--buttons-->
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton" class="btn btn-rounded-x btn-danger waves-effect text-left"
            data-url="/settings/quotes" data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable">{{ cleanLang(__('lang.save_changes')) }}</button>
    </div>
</form>
@endsection