<!-- action buttons -->
@include('pages.bol-payments.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="bol-payments-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($bol-payments) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--bol-payments table-->
<div class="card-embed-fix">
@include('pages.bol-payments.components.table.wrapper')
</div>
<!--bol-payments table-->