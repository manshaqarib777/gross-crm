<div class="row">
    <div class="col-lg-12">


        <!--to-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.send_email_to_client')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" name="send_email_to_client" class="form-control form-control-sm"
                    id="bol-send_email_to_client" autocomplete="off" placeholder="">
            </div>
        </div>

        <!--transaction id-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.email_cc')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" name="email_cc" class="form-control form-control-sm"
                    id="bol-email_cc" autocomplete="off" placeholder="{{ cleanLang(__('lang.email_cc_placeholder')) }}">
            </div>
        </div>

</div>