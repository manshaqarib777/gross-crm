<!--locations-->
<div class="pull-right bol-locations">
    <div class="card">
        <div class="card-header text-center">
            {{ cleanLang(__('lang.carrier_info')) }}
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_address')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_address"
                            autocomplete="off" value="{{ $bill->contact_address }}">
                    </td>
                    @else
                    <td class="x-location" > <span>{{ $bill->contact_address }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_dispatcher')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_dispatcher"
                            autocomplete="off" value="{{ $bill->contact_dispatcher }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->contact_dispatcher }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_driver')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_driver"
                            autocomplete="off" value="{{ $bill->contact_driver }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->contact_driver }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_truck')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_truck"
                            autocomplete="off" value="{{ $bill->contact_truck }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->contact_truck }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_trailer')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_trailer"
                            autocomplete="off" value="{{ $bill->contact_trailer }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->contact_trailer }}</span></td>
                    @endif
                </tr>
            </table>
        </div>
      </div>
</div>