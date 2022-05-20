<div class="row">
    <div class="col-lg-12">
        <div class="p-b-30">

            <table class="table table-bordered bol-payment-details">
                <tbody>
                    <tr>
                        <td>{{ cleanLang(__('lang.bol-payment_id')) }}</td>
                        <td>#{{ $bol-payment->bol-payment_id }}</td>
                    </tr>
                    <tr class="font-16 font-weight-600">
                            <td>{{ cleanLang(__('lang.amount')) }}</td>
                            <td>
                                {{ runtimeMoneyFormat($bol-payment->bol-payment_amount) }}</td>
                            </td>
                        </tr>
                    <tr>
                        <td>{{ cleanLang(__('lang.bol_id')) }}</td>
                        <td> {{ runtimeBolIdFormat($bol-payment->bol-payment_bolid) }}
                        </td>
                    </tr>
                    <tr>
                        <td>{{ cleanLang(__('lang.date')) }}</td>
                        <td>{{ runtimeDate($bol-payment->bol-payment_date) }}</td>
                    </tr>

                    <tr>
                        <td>{{ cleanLang(__('lang.bol-payment_method')) }}</td>
                        <td>{{ $bol-payment->bol-payment_gateway }}</td>
                    </tr>
                    @if(auth()->user()->is_team)
                    <tr>
                        <td>{{ cleanLang(__('lang.client')) }}</td>
                        <td>{{ $bol-payment->client_company_name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>{{ cleanLang(__('lang.project')) }}</td>
                        <td>{{ $bol-payment->project_title }}</td>
                    </tr>
                    <tr>
                        <td>{{ cleanLang(__('lang.notes')) }}</td>
                        <td>{{ $bol-payment->bol-payment_notes }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>