<!--ALL THIRD PART JAVASCRIPTS-->
<script src="public/vendor/js/vendor.footer.js?v={{ config('system.versioning') }}"></script>


@if(request()->segment(1) =="quotes" || request()->segment(3) =="quotes")
<!--nextloop.core.js-->
<script src="public/js/core/quote/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/quote/boot.js?v={{ config('system.versioning') }}"></script>

<!--EVENTS-->
<script src="public/js/core/quote/events.js?v={{ config('system.versioning') }}"></script>

<!--CORE-->
<script src="public/js/core/quote/app.js?v={{ config('system.versioning') }}"></script>

<!--BILLING-->
<script src="public/js/core/quote/billing.js?v={{ config('system.versioning') }}"></script>

@elseif(request()->segment(1) =="bols" || request()->segment(3) =="bols")
<!--nextloop.core.js-->
<script src="public/js/core/bol/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/bol/boot.js?v={{ config('system.versioning') }}"></script>

<!--EVENTS-->
<script src="public/js/core/bol/events.js?v={{ config('system.versioning') }}"></script>

<!--CORE-->
<script src="public/js/core/bol/app.js?v={{ config('system.versioning') }}"></script>

<!--BILLING-->
<script src="public/js/core/bol/billing.js?v={{ config('system.versioning') }}"></script>
@else
<!--nextloop.core.js-->
<script src="public/js/core/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/boot.js?v={{ config('system.versioning') }}"></script>

<!--EVENTS-->
<script src="public/js/core/events.js?v={{ config('system.versioning') }}"></script>

<!--CORE-->
<script src="public/js/core/app.js?v={{ config('system.versioning') }}"></script>

<!--BILLING-->
<script src="public/js/core/billing.js?v={{ config('system.versioning') }}"></script>
@endif

<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{config('system.settings_google_map_api')}}&libraries=places" ></script>
@stack('extraScripts')

<!--project page charts-->
@if(@config('visibility.projects_d3_vendor'))
<script src="public/vendor/js/d3/d3.min.js?v={{ config('system.versioning') }}"></script>
<script src="public/vendor/js/c3-master/c3.min.js?v={{ config('system.versioning') }}"></script>
@endif

<!--form builder-->
@if(@config('visibility.web_form_builder'))
<script src="public/vendor/js/formbuilder/form-builder.min.js?v={{ config('system.versioning') }}"></script>
<script src="public/js/webforms/webforms.js?v={{ config('system.versioning') }}"></script>
@endif