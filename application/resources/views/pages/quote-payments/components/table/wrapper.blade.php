<!--bulk actions-->
@include('pages.quote-payments.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.quote-payments.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.quote-payments.components.misc.filter-quote-payments')
@endif
<!--filter-->