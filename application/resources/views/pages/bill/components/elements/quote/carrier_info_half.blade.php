<!--locations-->
<div class="pull-left bol-locations">
    <div class="card">
        <div class="card-header text-center">
            {{ cleanLang(__('lang.carrier_info')) }}
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_mc_dot_number')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_mc_dot_number"
                            autocomplete="off" value="{{ $bill->contact_mc_dot_number }}">
                    </td>
                    @else
                    <td class="x-location" > <span>{{ $bill->contact_mc_dot_number }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_name')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_name"
                            autocomplete="off" value="{{ $bill->contact_name }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->contact_name }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_phone')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_phone"
                            autocomplete="off" value="{{ $bill->contact_phone }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->contact_phone }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_term')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_term"
                            autocomplete="off" value="{{ $bill->contact_term }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->contact_term }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_fax')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="contact_fax"
                            autocomplete="off" value="{{ $bill->contact_fax }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->contact_fax }}</span></td>
                    @endif
                </tr>
            </table>
        </div>
      </div>
</div>