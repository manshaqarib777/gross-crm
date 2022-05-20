<!--bulk actions-->
@include('pages.bols.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.bols.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.bols.components.misc.filter-bols')
@endif
<!--filter-->