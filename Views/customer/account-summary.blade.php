<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="row justify-between border-bottom">
                <div class="col-md-6">
                    <p>Customer: {{ $accountSummary['CustomerName'] ?? '' }}</p>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <p>Credit Limit: {{ price_format($accountSummary['CreditLimit'] ?? 0) }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <p>Terms: {{ $accountSummary['TermsDescription'] ?? '' }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">

                        <p>Last Payment: {{ carbon_date($accountSummary['DateOfLastPayment']) }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <p>Amount: {{ price_format($accountSummary['LastPayAmount'] ?? 0) }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <p>Open Order Amount: {{ price_format($accountSummary['OpenOrderAmount'] ?? 0) }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <p>No. open invoices: {{ $accountSummary['NumOpenInv'] ?? '' }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <p>Year to Date Spend: {{ price_format($accountSummary['SalesYearToDate'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="row justify-between border-bottom mt-3">

                <div class="col-md-12">
                    <div class="form-group">
                        <p> {{ __('Aged Debt :') }} </p>
                    </div>
                </div>

                @for($i=1; $i<=5; $i++)
                    @if ($accountSummary["TradeAgePeriod{$i}Text"] != null)
                        <div class="col">
                            <div class="form-group">
                                <p>{{ $accountSummary["TradeAgePeriod{$i}Text"] }}:
                                    {{ price_format($accountSummary["TradeAgePeriod{$i}Amount"] ?? 0) }}</p>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>

            <div class="row justify-between mt-4">

                <div class="col-md-4">
                    <div class="form-group">
                        <p>Current: {{ price_format($accountSummary['TradeBillingPeriodAmount'] ?? 0) }}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <p>Total Due: {{ price_format($accountSummary['TradeAmountDue'] ?? 0) }}</p>
                    </div>
                </div>

            </div>
            @if (customer(true)->can('invoices.view'))
                <span class="text-center d-block mt-3">
                    <a href="{{ route('frontend.invoices.index') }}" class="btn btn-sm btn-outline-info"><i class="icon-file"></i> View Invoices</a>
                </span>
            @endif
        </div>
    </div>
</div>
