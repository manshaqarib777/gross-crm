<!-- action buttons -->
@include('pages.quote-payments.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="quote-payments-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($quote-payments) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--quote-payments table-->
<div class="card-embed-fix">
@include('pages.quote-payments.components.table.wrapper')
</div>
<!--quote-payments table-->