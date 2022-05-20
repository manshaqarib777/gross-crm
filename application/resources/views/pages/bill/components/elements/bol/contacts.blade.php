    <!--dates-->
    <div class="pull-left bol-dates">
        <table>
            <tr>
                <td class="x-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_person')) }}: </td>
                @if(config('visibility.bill_mode') == 'editing')
                <td><input type="text" class="form-control form-control-xs" name="contact_person"
                        autocomplete="off" value="{{ $bill->contact_person }}">
                </td>
                @else
                <td class="x-location" > <span>{{ $bill->contact_person }}</span></td>
                @endif
            </tr>
            <tr>
                <td class="x-delivery-location-lang font-weight-bold" >{{ cleanLang(__('lang.contact_details')) }}: </td>
                @if(config('visibility.bill_mode') == 'editing')
                <td><input type="text" class="form-control form-control-xs" name="contact_details"
                        autocomplete="off" value="{{ $bill->contact_details }}">
                </td>
                @else
                <td class="x-delivery-location"> <span>{{ $bill->contact_details }}</span></td>
                @endif
            </tr>
        </table>
    </div>