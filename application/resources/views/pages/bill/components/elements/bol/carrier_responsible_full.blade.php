<!--locations-->
<div class="pull-right bol-locations">
    <div class="card">
        <div class="card-header text-center">
            {{ cleanLang(__('lang.carrier_responsible')) }}
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.cargo_commodity')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="cargo_commodity"
                            autocomplete="off" value="{{ $bill->cargo_commodity }}">
                    </td>
                    @else
                    <td class="x-location" > <span>{{ $bill->cargo_commodity }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.cargo_weight')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="cargo_weight"
                            autocomplete="off" value="{{ $bill->cargo_weight }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->cargo_weight }}</span></td>
                    @endif
                </tr>
            </table>
        </div>
      </div>
</div>