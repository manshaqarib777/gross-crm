<!--locations-->
<div class="pull-left bol-locations">
    <div class="card">
        <div class="card-header text-center">
            {{ cleanLang(__('lang.load_info')) }}
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_mode')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_mode"
                            autocomplete="off" value="{{ $bill->load_mode }}">
                    </td>
                    @else
                    <td class="x-location" > <span>{{ $bill->load_mode }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_trailer_type')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_trailer_type"
                            autocomplete="off" value="{{ $bill->load_trailer_type }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_trailer_type }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_trailer_size')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_trailer_size"
                            autocomplete="off" value="{{ $bill->load_trailer_size }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_trailer_size }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_linear_feet')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_linear_feet"
                            autocomplete="off" value="{{ $bill->load_linear_feet }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_linear_feet }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_temperature')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_temperature"
                            autocomplete="off" value="{{ $bill->load_temperature }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_temperature }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_length')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_length"
                            autocomplete="off" value="{{ $bill->load_length }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_length }}</span></td>
                    @endif
                </tr>
            </table>
        </div>
      </div>
</div>