<!--locations-->
<div class="pull-left bol-locations">
    <div class="card">
        <div class="card-header text-center">
            {{ cleanLang(__('lang.carrier_responsible')) }}
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.carrier_unloading')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="carrier_unloading"
                            autocomplete="off" value="{{ $bill->carrier_unloading }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->carrier_unloading }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.carrier_pallet_exchange')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="carrier_pallet_exchange"
                            autocomplete="off" value="{{ $bill->carrier_pallet_exchange }}">
                    </td>
                    @else
                    <td class="x-location" > <span>{{ $bill->carrier_pallet_exchange }}</span></td>
                    @endif
                </tr>
            </table>
        </div>
      </div>
</div>