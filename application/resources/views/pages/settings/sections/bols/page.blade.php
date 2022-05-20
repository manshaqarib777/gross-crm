@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form" id="settingsFormBols">
    <!--form text tem-->
    <div class="form-group row">
        <label class="col-12 control-label col-form-label">{{ cleanLang(__('lang.bol_prefix')) }}</label>
        <div class="col-12">
            <input type="text" class="form-control form-control-sm" id="settings_bols_prefix"
                name="settings_bols_prefix" value="{{ $settings->settings_bols_prefix ?? '' }}">
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
            <input type="number" class="form-control form-control-sm" id="settings_bols_recurring_grace_period"
                name="settings_bols_recurring_grace_period"
                value="{{ $settings->settings_bols_recurring_grace_period ?? '' }}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.terms_and_conditions')) }}</label>
        <div class="col-12 p-t-5">
            <textarea class="form-control form-control-sm tinymce-textarea" rows="5"
                name="settings_bols_default_terms_conditions" id="settings_bols_default_terms_conditions">
                    {{ $settings->settings_bols_default_terms_conditions ?? '' }}
                </textarea>
        </div>
    </div>


    <!--form checkbox item-->
    <div class="form-group form-group-checkbox row">
        <div class="col-12 p-t-5">
            <input type="checkbox" id="settings_bols_show_view_status"
                name="settings_bols_show_view_status" class="filled-in chk-col-light-blue"
                {{ runtimePrechecked($settings['settings_bols_show_view_status'] ?? '') }}>
            <label for="settings_bols_show_view_status">{{ cleanLang(__('lang.show_if_client_has_opened')) }}</label>
        </div>
    </div>

    <div>
        <!--settings documentation help-->
        <a href="https://growcrm.io/documentation/bol-settings/" target="_blank"
            class="btn btn-sm btn-info help-documentation"><i class="ti-info-alt"></i>
            {{ cleanLang(__('lang.help_documentation')) }}</a>
    </div>

    <!--buttons-->
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton" class="btn btn-rounded-x btn-danger waves-effect text-left"
            data-url="/settings/bols" data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable">{{ cleanLang(__('lang.save_changes')) }}</button>
    </div>
</form>
@endsection