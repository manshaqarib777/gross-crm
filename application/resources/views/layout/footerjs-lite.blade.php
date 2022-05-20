<!--ALL THIRD PART JAVASCRIPTS-->
<script src="public/vendor/js/vendor-lite.footer.js?v={{ config('system.versioning') }}"></script>


@if(request()->segment(1) =="quotes")
        <!--nextloop.core.js-->
<script src="public/js/core/quote/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/quote/boot-lite.js?v={{ config('system.versioning') }}"></script>
@elseif(request()->segment(1) =="bols")
        <!--nextloop.core.js-->
<script src="public/js/core/bol/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/bol/boot-lite.js?v={{ config('system.versioning') }}"></script>
@else
<!--nextloop.core.js-->
<script src="public/js/core/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/boot-lite.js?v={{ config('system.versioning') }}"></script>
@endif