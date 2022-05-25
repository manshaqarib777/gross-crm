<!--locations-->
<div class="pull-right bol-locations">
    <div class="card">
        <div class="card-header text-center">
            {{ cleanLang(__('lang.load_info')) }}
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_pallet_case_count')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_pallet_case_count"
                            autocomplete="off" value="{{ $bill->load_pallet_case_count }}">
                    </td>
                    @else
                    <td class="x-location" > <span>{{ $bill->load_pallet_case_count }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_hazmat')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_hazmat"
                            autocomplete="off" value="{{ $bill->load_hazmat }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_hazmat }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_requirements')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_requirements"
                            autocomplete="off" value="{{ $bill->load_requirements }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_requirements }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_instructions')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_instructions"
                            autocomplete="off" value="{{ $bill->load_instructions }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_instructions }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_width')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_width"
                            autocomplete="off" value="{{ $bill->load_width }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_width }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.load_height')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="load_height"
                            autocomplete="off" value="{{ $bill->load_height }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->load_height }}</span></td>
                    @endif
                </tr>
            </table>
        </div>
      </div>
</div>