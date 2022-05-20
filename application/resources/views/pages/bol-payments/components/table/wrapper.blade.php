<!--bulk actions-->
@include('pages.bol-payments.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.bol-payments.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.bol-payments.components.misc.filter-bol-payments')
@endif
<!--filter-->