@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">

        <!-- Page Title & Bread Crumbs -->
        @include('misc.heading-crumbs')
        <!--Page Title & Bread Crumbs -->


        <!-- action buttons -->
        @include('pages.quote-payments.components.misc.list-page-actions')
        <!-- action buttons -->

    </div>
    <!--page heading-->

    <!--stats panel-->
    @if(auth()->user()->is_team)
    <div class="stats-wrapper" id="quote-payments-stats-wrapper">
    @include('misc.list-pages-stats')
    </div>
    @endif
    <!--stats panel-->

    <!-- page content -->
    <div class="row">
        <div class="col-12">
            <!--quote-payments table-->
            @include('pages.quote-payments.components.table.wrapper')
            <!--quote-payments table-->
        </div>
    </div>
    <!--page content -->

</div>
<!--main content -->
<!--dynamic load quote-payment quote-payment (dynamic_trigger_dom)-->
@if(config('visibility.dynamic_load_modal'))
<a href="javascript:void(0)" id="dynamic-quote-payment-content"
    class="show-modal-button edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-modal-title="{{ cleanLang(__('lang.quote-payment')) }}"
    data-target="#plainModal" data-url="{{ url('/quote-payments/'.request()->route('quote-payment').'?ref=list') }}"
    data-loading-target="plainModalBody"></a>
@endif
@endsection