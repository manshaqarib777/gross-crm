<div class="row">
    <div class="col-lg-12">
        <div class="p-b-30">

            <table class="table table-bordered quote-payment-details">
                <tbody>
                    <tr>
                        <td>{{ cleanLang(__('lang.quote-payment_id')) }}</td>
                        <td>#{{ $quote-payment->quote-payment_id }}</td>
                    </tr>
                    <tr class="font-16 font-weight-600">
                            <td>{{ cleanLang(__('lang.amount')) }}</td>
                            <td>
                                {{ runtimeMoneyFormat($quote-payment->quote-payment_amount) }}</td>
                            </td>
                        </tr>
                    <tr>
                        <td>{{ cleanLang(__('lang.quote_id')) }}</td>
                        <td> {{ runtimeQuoteIdFormat($quote-payment->quote-payment_quoteid) }}
                        </td>
                    </tr>
                    <tr>
                        <td>{{ cleanLang(__('lang.date')) }}</td>
                        <td>{{ runtimeDate($quote-payment->quote-payment_date) }}</td>
                    </tr>

                    <tr>
                        <td>{{ cleanLang(__('lang.quote-payment_method')) }}</td>
                        <td>{{ $quote-payment->quote-payment_gateway }}</td>
                    </tr>
                    @if(auth()->user()->is_team)
                    <tr>
                        <td>{{ cleanLang(__('lang.client')) }}</td>
                        <td>{{ $quote-payment->client_company_name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>{{ cleanLang(__('lang.project')) }}</td>
                        <td>{{ $quote-payment->project_title }}</td>
                    </tr>
                    <tr>
                        <td>{{ cleanLang(__('lang.notes')) }}</td>
                        <td>{{ $quote-payment->quote-payment_notes }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>