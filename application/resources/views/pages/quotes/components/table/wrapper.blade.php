<!--bulk actions-->
@include('pages.quotes.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.quotes.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.quotes.components.misc.filter-quotes')
@endif
<!--filter-->