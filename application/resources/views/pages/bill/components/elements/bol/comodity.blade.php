    <!--dates-->
    <div class="pull-right bol-dues">
        <table>
            <tr>
                <td class="x-payments-lang font-weight-bold" >{{ cleanLang(__('lang.cargo_commodity')) }}: </td>
                @if(config('visibility.bill_mode') == 'editing')
                <td><input type="text" class="form-control form-control-xs" name="cargo_commodity"
                        autocomplete="off" value="{{ $bill->cargo_commodity }}">
                </td>
                @else
                <td class="x-payments" > <span>{{ $bill->cargo_commodity }}</span></td>
                @endif
            </tr>
            <tr>
                <td class="x-balance-due-lang font-weight-bold" >{{ cleanLang(__('lang.cargo_weight')) }}: </td>
                @if(config('visibility.bill_mode') == 'editing')
                <td><input type="text" class="form-control form-control-xs" name="cargo_weight"
                        autocomplete="off" value="{{ $bill->cargo_weight }}">
                </td>
                @else
                <td class="x-balance-due"> <span>{{ $bill->cargo_weight }}</span></td>
                @endif
            </tr>
        </table>
    </div>