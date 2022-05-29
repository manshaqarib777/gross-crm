<!--locations-->
<div class="pull-right bol-locations">
    <div class="card">
        <div class="card-header text-center">
            {{ cleanLang(__('lang.delivery_location')) }}
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.delivery_location')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs google_location" name="delivery_location"
                            autocomplete="off" value="{{ $bill->delivery_location }}">
                    </td>
                    @else
                    <td class="x-location" > <span>{{ $bill->delivery_location }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.delivery_telefax')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="delivery_telefax"
                            autocomplete="off" value="{{ $bill->delivery_telefax }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->delivery_telefax }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.delivery_phone')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="delivery_phone"
                            autocomplete="off" value="{{ $bill->delivery_phone }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->delivery_phone }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.delivery_email')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="delivery_email"
                            autocomplete="off" value="{{ $bill->delivery_email }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->delivery_email }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.delivery_gstin')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="delivery_gstin"
                            autocomplete="off" value="{{ $bill->delivery_gstin }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->delivery_gstin }}</span></td>
                    @endif
                </tr>
            </table>
        </div>
      </div>
</div>


