<!-- action buttons -->
@include('pages.quotes.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="quotes-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($quotes) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--quotes table-->
<div class="card-embed-fix">
@include('pages.quotes.components.table.wrapper')
</div>
<!--quotes table-->