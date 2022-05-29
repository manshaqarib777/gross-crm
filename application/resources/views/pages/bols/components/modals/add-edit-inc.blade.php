<style>
    .pac-container {
        z-index: 10000 !important;
    }
    .form-group {
        margin-bottom: 1rem !important;
    }

</style>
<div class="row" id="js-trigger-bols-modal-add-edit" data-payload="{{ $page['section'] ?? '' }}">
    <div class="col-lg-12">

        <!--meta data - creatd by-->
        @if(isset($page['section']) && $page['section'] == 'edit')
        <div class="modal-meta-data">
            <small><strong>{{ cleanLang(__('lang.created_by')) }}:</strong> {{ $bol->first_name }}
                {{ $bol->last_name}} |
                {{ runtimeDate($bol->bill_created) }}</small>
        </div>
        @endif



        <!--client and project-->
        @if(config('visibility.bol_modal_client_project_fields'))
        <!--client-->
        <div class="client-selector">

            <!--existing client-->
            <div class="client-selector-container" id="client-existing-container">
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">{{ cleanLang(__('lang.company')) }}*</label>
                    <div class="col-sm-12 col-lg-9">
                        <!--select2 basic search-->
                        <select name="bill_clientid" id="bill_clientid"
                            class="clients_and_projects_toggle form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                            data-projects-dropdown="bill_projectid" data-feed-request-type="clients_projects"
                            data-ajax--url="{{ url('/') }}/feed/company_names">
                            <!--regular bols-->
                            @if(isset($bol->bill_clientid) && $bol->bill_clientid != '')
                            <option value="{{ $bol->bill_clientid ?? '' }}">{{ $bol->client_company_name }}
                            </option>
                            @endif
                            <!--creating bol from an expense-->
                            @if(config('visibility.bol_from_expense_client_name'))
                            <option value="{{ $expense->expense_clientid ?? '' }}">{{ $expense->client_company_name }}
                            </option>
                            @endif
                        </select>
                    </div>
                </div>
                <!--projects-->
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.project')) }}</label>
                    <div class="col-sm-12 col-lg-9">
                        <select class="select2-basic form-control form-control-sm dynamic_bill_projectid" data-allow-clear="true"
                            id="bill_projectid" name="bill_projectid" disabled>
                        </select>
                    </div>
                </div>
            </div>

            <!--new client-->
            <div class="client-selector-container hidden" id="client-new-container">
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-4 text-left control-label col-form-label required">{{ cleanLang(__('lang.company_name')) }}*</label>
                    <div class="col-sm-12 col-lg-8">
                        <input type="text" class="form-control form-control-sm" id="client_company_name"
                            name="client_company_name">
                    </div>
                </div>

                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-4 text-left control-label col-form-label required">{{ cleanLang(__('lang.first_name')) }}*</label>
                    <div class="col-sm-12 col-lg-8">
                        <input type="text" class="form-control form-control-sm" id="first_name" name="first_name"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-4 text-left control-label col-form-label required">{{ cleanLang(__('lang.last_name')) }}*</label>
                    <div class="col-sm-12 col-lg-8">
                        <input type="text" class="form-control form-control-sm" id="last_name" name="last_name"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-4 text-left control-label col-form-label required">{{ cleanLang(__('lang.email_address')) }}*</label>
                    <div class="col-sm-12 col-lg-8">
                        <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="">
                    </div>
                </div>
            </div>

            <!--option buttons-->
            <div class="client-selector-links">
                <a href="javascript:void(0)" class="client-type-selector" data-type="new"
                    data-target-container="client-new-container">@lang('lang.new_company')</a> |
                <a href="javascript:void(0)" class="client-type-selector active" data-type="existing"
                    data-target-container="client-existing-container">@lang('lang.existing_company')</a>
            </div>

            <!--client type indicator-->
            <input type="hidden" name="client-selection-type" id="client-selection-type" value="existing">
        </div>

        @endif

        
        <!--bol date-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.bol_date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm pickadate" name="bill_date_add_edit"
                    autocomplete="off" value="{{ runtimeDatepickerDate($bol->bill_date ?? '') }}">
                <input class="mysql-date" type="hidden" name="bill_date" id="bill_date_add_edit"
                    value="{{ $bol->bill_date ?? '' }}">
            </div>
        </div>

        <!--due date-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.due_date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" name="bill_due_date_add_edit"
                    autocomplete="off" value="{{ runtimeDatepickerDate($bol->bill_due_date ?? '') }}">
                <input class="mysql-date" type="hidden" name="bill_due_date" id="bill_due_date_add_edit"
                    value="{{ $bol->bill_due_date ?? '' }}">
            </div>
        </div>



        <!--clients projects-->
        @if(config('visibility.bol_modal_clients_projects'))
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">{{ cleanLang(__('lang.project')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="bill_projectid" name="bill_projectid">
                    @foreach(config('settings.clients_projects') as $project)
                    <option value="{{ $project->project_id ?? '' }}">{{ $project->project_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        <!--bol category-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">{{ cleanLang(__('lang.category')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="bill_categoryid" name="bill_categoryid">
                    @foreach($categories as $category)
                    <option value="{{ $category->category_id }}"
                        {{ runtimePreselected($bol->bill_categoryid ?? '', $category->category_id) }}>{{
                                runtimeLang($category->category_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_person')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm" name="contact_person" id="contact_person"
                    autocomplete="off" value="{{ $bol->contact_person ?? '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_details')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm" name="contact_details" id="contact_details"
                    autocomplete="off" value="{{ $bol->contact_details ?? '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.cargo_commodity')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm" name="cargo_commodity" id="cargo_commodity"
                    autocomplete="off" value="{{ $bol->cargo_commodity ?? '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.cargo_weight')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm" name="cargo_weight" id="cargo_weight"
                    autocomplete="off" value="{{ $bol->cargo_weight ?? '' }}">
            </div>
        </div> --}}
        <!--locations section-->

            <div class="spacer row">
                <div class="col-sm-12 col-lg-8">
                    <span class="title">{{ cleanLang(__('lang.locations')) }}</span class="title">
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="switch  text-right">
                        <label>
                            <input type="checkbox" name="add_client_locations" id="add_client_locations"
                                class="js-switch-toggle-hidden-content" data-target="add_client_locations_section">
                            <span class="lever switch-col-light-blue"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div id="add_client_locations_section" class="hidden">
                <div class="row">
                    <div class="col-sm-6">
                        <!--bol pickup location-->
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.pickup_location')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm google_location" name="pickup_location" id="pickup_location"  value="{{ $bol->pickup_location ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.pickup_date')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="pickup_date" id="pickup_date"
                                    autocomplete="off" value="{{ $bol->pickup_date ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.pickup_time')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="pickup_time" id="pickup_time"
                                    autocomplete="off" value="{{ $bol->pickup_time ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.pickup_email')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="pickup_email" id="pickup_email"
                                    autocomplete="off" value="{{ $bol->pickup_email ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.pickup_gstin')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="pickup_gstin" id="pickup_gstin"
                                    autocomplete="off" value="{{ $bol->pickup_gstin ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!--bol delivery location-->
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.delivery_location')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm google_location" name="delivery_location" id="delivery_location"
                                    autocomplete="off" value="{{ $bol->delivery_location ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.delivery_date')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="delivery_date" id="delivery_date"
                                    autocomplete="off" value="{{ $bol->delivery_date ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.delivery_time')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="delivery_time" id="delivery_time"
                                    autocomplete="off" value="{{ $bol->delivery_time ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.delivery_email')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="delivery_email" id="delivery_email"
                                    autocomplete="off" value="{{ $bol->delivery_email ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.delivery_gstin')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="delivery_gstin" id="delivery_gstin"
                                    autocomplete="off" value="{{ $bol->delivery_gstin ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="line"></div>

        <!--locations section-->


        <!--carrier info section-->
            <div class="spacer row">
                <div class="col-sm-12 col-lg-8">
                    <span class="title">{{ cleanLang(__('lang.carrier_info')) }}</span class="title">
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="switch  text-right">
                        <label>
                            <input type="checkbox" name="add_client_option_carrier_info" id="add_client_option_carrier_info"
                                class="js-switch-toggle-hidden-content" data-target="add_client_carrier_info_section">
                            <span class="lever switch-col-light-blue"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div id="add_client_carrier_info_section" class="hidden">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_mc_dot_number')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_mc_dot_number" id="contact_mc_dot_number"  value="{{ $bol->contact_mc_dot_number ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_name')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_name" id="contact_name"
                                    autocomplete="off" value="{{ $bol->contact_name ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_phone')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_phone" id="contact_phone"
                                    autocomplete="off" value="{{ $bol->contact_phone ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_term')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_term" id="contact_term"
                                    autocomplete="off" value="{{ $bol->contact_term ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_fax')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_fax" id="contact_fax"
                                    autocomplete="off" value="{{ $bol->contact_fax ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!--bol delivery location-->
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_address')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_address" id="contact_address"
                                    autocomplete="off" value="{{ $bol->contact_address ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_dispatcher')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_dispatcher" id="contact_dispatcher"
                                    autocomplete="off" value="{{ $bol->contact_dispatcher ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_driver')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_driver" id="contact_driver"
                                    autocomplete="off" value="{{ $bol->contact_driver ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_truck')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_truck" id="contact_truck"
                                    autocomplete="off" value="{{ $bol->contact_truck ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.contact_trailer')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="contact_trailer" id="contact_trailer"
                                    autocomplete="off" value="{{ $bol->contact_trailer ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="line"></div>

        <!--carrier info section-->


        <!--load info section-->
            <div class="spacer row">
                <div class="col-sm-12 col-lg-8">
                    <span class="title">{{ cleanLang(__('lang.load_info')) }}</span class="title">
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="switch  text-right">
                        <label>
                            <input type="checkbox" name="add_client_option_load_info" id="add_client_option_load_info"
                                class="js-switch-toggle-hidden-content" data-target="add_client_load_info_section">
                            <span class="lever switch-col-light-blue"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div id="add_client_load_info_section" class="hidden">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_mode')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_mode" id="load_mode"  value="{{ $bol->load_mode ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_trailer_type')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_trailer_type" id="load_trailer_type"
                                    autocomplete="off" value="{{ $bol->load_trailer_type ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_trailer_size')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_trailer_size" id="load_trailer_size"
                                    autocomplete="off" value="{{ $bol->load_trailer_size ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_linear_feet')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_linear_feet" id="load_linear_feet"
                                    autocomplete="off" value="{{ $bol->load_linear_feet ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_temperature')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_temperature" id="load_temperature"
                                    autocomplete="off" value="{{ $bol->load_temperature ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_length')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_length" id="load_length"
                                    autocomplete="off" value="{{ $bol->load_length ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!--bol delivery location-->
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_pallet_case_count')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_pallet_case_count" id="load_pallet_case_count"
                                    autocomplete="off" value="{{ $bol->load_pallet_case_count ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_hazmat')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_hazmat" id="load_hazmat"
                                    autocomplete="off" value="{{ $bol->load_hazmat ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_requirements')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_requirements" id="load_requirements"
                                    autocomplete="off" value="{{ $bol->load_requirements ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_instructions')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_instructions" id="load_instructions"
                                    autocomplete="off" value="{{ $bol->load_instructions ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_width')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_width" id="load_width"
                                    autocomplete="off" value="{{ $bol->load_width ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.load_height')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="load_height" id="load_height"
                                    autocomplete="off" value="{{ $bol->load_height ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="line"></div>
        <!--load info section-->

        <!--carrier responsible section-->
        
            <div class="spacer row">
                <div class="col-sm-12 col-lg-8">
                    <span class="title">{{ cleanLang(__('lang.carrier_responsible')) }}</span class="title">
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="switch  text-right">
                        <label>
                            <input type="checkbox" name="add_client_option_carrier_responsible" id="add_client_option_carrier_responsible"
                                class="js-switch-toggle-hidden-content" data-target="add_client_carrier_responsible_section">
                            <span class="lever switch-col-light-blue"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div id="add_client_carrier_responsible_section" class="hidden">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.carrier_unloading')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="carrier_unloading" id="carrier_unloading"  value="{{ $bol->carrier_unloading ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.carrier_pallet_exchange')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="carrier_pallet_exchange" id="carrier_pallet_exchange"
                                    autocomplete="off" value="{{ $bol->carrier_pallet_exchange ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!--bol delivery location-->
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.cargo_commodity')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="cargo_commodity" id="cargo_commodity"
                                    autocomplete="off" value="{{ $bol->cargo_commodity ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.cargo_weight')) }}*</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control  form-control-sm" name="cargo_weight" id="cargo_weight"
                                    autocomplete="off" value="{{ $bol->cargo_weight ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="line"></div>
        
        <!--carrier responsible section-->
        


        <!--otions toggle-->
        <div class="spacer row">
            <div class="col-sm-12 col-lg-8">
                <span class="title">{{ cleanLang(__('lang.additional_information')) }}</span class="title">
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="switch  text-right">
                    <label>
                        <input type="checkbox" class="js-switch-toggle-hidden-content"
                            data-target="edit_bill_recurring_toggle">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="hidden" id="edit_bill_recurring_toggle">
            <!--tags-->
            <div class="form-group row">
                <label class="col-12 text-left control-label col-form-label">{{ cleanLang(__('lang.tags')) }}</label>
                <div class="col-12">
                    <select name="tags" id="tags"
                        class="form-control form-control-sm select2-multiple {{ runtimeAllowUserTags() }} select2-hidden-accessible"
                        multiple="multiple" tabindex="-1" aria-hidden="true">
                        <!--array of selected tags-->
                        @if(isset($page['section']) && $page['section'] == 'edit')
                        @foreach($bol->tags as $tag)
                        @php $selected_tags[] = $tag->tag_title ; @endphp
                        @endforeach
                        @endif
                        <!--/#array of selected tags-->
                        @foreach($tags as $tag)
                        <option value="{{ $tag->tag_title }}"
                            {{ runtimePreselectedInArray($tag->tag_title ?? '', $selected_tags ?? []) }}>
                            {{ $tag->tag_title }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- notes-->
            <div class="form-group row">
                <label class="col-12 text-left control-label col-form-label">{{ cleanLang(__('lang.notes')) }}</label>
                <div class="col-12">
                    <textarea id="bill_notes" name="bill_notes"
                        class="tinymce-textarea">{{ $bol->bill_notes ?? '' }}</textarea>
                </div>
            </div>


            <!-- terms-->
            <div class="form-group row">
                <label
                    class="col-12 text-left control-label col-form-label">{{ cleanLang(__('lang.terms_and_conditions')) }}</label>
                <div class="col-12">
                    <textarea id="bill_terms" name="bill_terms" class="tinymce-textarea">
                        @if(isset($page['section']) && $page['section'] == 'create')
                        {{ config('system.settings_bols_default_terms_conditions') }}
                        @else
                        {{ $bol->bill_terms ?? '' }}
                        @endif                 
                </textarea>
                </div>
            </div>
        </div>
        <!--/#options toggle-->



        <!--source-->
        <input type="hidden" name="source" value="{{ request('source') }}">

        <!--expenses payload-->
        @if(config('visibility.bol_modal_expenses_payload'))
        <input type="hidden" name="expense_payload[]" value="{{ config('settings.expense_id') }}">
        @endif

        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
            </div>
        </div>

        <!--recurring notes-->
        <div class="alert alert-info m-t-10"><i class="sl-icon-refresh text-warning"></i>
            {{ cleanLang(__('lang.recurring_bol_options_info')) }}</div>
    </div>
</div>
<script>
        const google_map = document.querySelectorAll(".google_location");
        google_map.forEach(element => {
            var autocomplete = new google.maps.places.Autocomplete(element);
        });

    
</script>
