<!-- action buttons -->
@include('pages.bols.components.misc.list-page-actions')
<!-- action buttons -->

<!--stats panel-->
@if(auth()->user()->is_team)
<div id="bols-stats-wrapper" class="stats-wrapper card-embed-fix">
@if (@count($bols) > 0) @include('misc.list-pages-stats') @endif
</div>
@endif
<!--stats panel-->

<!--bols table-->
<div class="card-embed-fix">
@include('pages.bols.components.table.wrapper')
</div>
<!--bols table-->