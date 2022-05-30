<!--locations-->
<div class="pull-left bol-locations">
    <div class="card">
        <div class="card-header text-center">
            {{ cleanLang(__('lang.pickup_location')) }}
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.pickup_location')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs google_location" name="pickup_location"
                            autocomplete="off" value="{{ $bill->pickup_location }}">
                    </td>
                    @else
                    <td class="x-location" > <span>{{ $bill->pickup_location }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.pickup_telefax')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="pickup_telefax"
                            autocomplete="off" value="{{ $bill->pickup_telefax }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->pickup_telefax }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.pickup_phone')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="pickup_phone"
                            autocomplete="off" value="{{ $bill->pickup_phone }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->pickup_phone }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.pickup_email')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="pickup_email"
                            autocomplete="off" value="{{ $bill->pickup_email }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->pickup_email }}</span></td>
                    @endif
                </tr>
                {{-- <tr>
                    <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.pickup_gstin')) }}: </td>
                    @if(config('visibility.bill_mode') == 'editing')
                    <td><input type="text" class="form-control form-control-xs" name="pickup_gstin"
                            autocomplete="off" value="{{ $bill->pickup_gstin }}">
                    </td>
                    @else
                    <td class="x-delivery-location"> <span>{{ $bill->pickup_gstin }}</span></td>
                    @endif
                </tr> --}}
            </table>
        </div>
      </div>
</div>

@push('extraScripts')    
<script>
        const google_map = document.querySelectorAll(".google_location");
        google_map.forEach(element => {
            var autocomplete = new google.maps.places.Autocomplete(element);
        });

    
</script>
@endpush
