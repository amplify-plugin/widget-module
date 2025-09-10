@php
    $pageTitle = 'Payment';
    push_js([
        'js/jquery-migrate.js',
        'https://www.cenpos.com/Plugins/porthole.min.js',
        'https://www.cenpos.com/Plugins/jquery.simplewebpay.js',
        'js/cenpos-payment.js'
    ], 'custom-script');

    push_js('
            $(document).ready(function () {
            setTimeout(function () {
                setupPaymentSystem("' .($verifyingPost ?? '') .'", ' . json_encode($customer ?? []).');
            }, 1000);

            $("#processPayment").on(\'click\', \'[data-dismiss="modal"]\', function () {
                $("#modelBackdrop").html("");
            });
        });
    ', 'footer-script');
@endphp

<div {!! $htmlAttributes !!}>
    @if (!empty($errors))
        <!-- Display errors if there are any -->
        <div class="d-flex justify-content-center align-items-start" style="padding-top: 20px;">
            <div class="alert alert-danger text-center" style="max-width: 600px;">
                <h4>There were some issues with your request:</h4>
                <ul class="list-unstyled">
                    @foreach ($errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <a class="btn btn-secondary mt-3" href="{{ url('invoices')}}">Go Back</a>
            </div>
        </div>
    @else
        <!-- Show the payment form content if no errors -->
        <div class="row d-flex justify-content-center">
            <div class="col-xs-12 col-md-8 col-lg-5">
                <div id="CenposPlugin" style="min-height: 428px">Loading...</div>
                <form method="POST">
                    <input class="btn btn-primary" type="button" id="paySubmit" value="Continue">
                    <a class="btn btn-danger" href="{{ url('invoices') }}">Cancel</a>

                <input type="hidden" name="cardname" value="">
                <input type="hidden" name="cc_token" value="">
                <input type="hidden" name="last_four" value="">
                <input type="hidden" name="cc_cardtype" value="">
                <input type="hidden" name="cardexpmonth" value="">
                <input type="hidden" name="cardexpyear" value="">
                <input type="hidden" name="cardtype" value="">

            </form>
        </div>
    </div>
    <div id="processPayment" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <form action="{{ route('customer.cenpos-invoice-pay.submit') }}" method="post">
                    <input type="hidden" class="card-token" name="card_token"/>
                    <input type="hidden" name="data_token" value="{{ $payableInvoicesId }}">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure?</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Do you really want to use this card? This process cannot be undone.</p>
                        <table class="table table-bordered mt-2">
                            <tbody>
                            <tr>
                                <th scope="row">Name On Card</th>
                                <td class="card-name"></td>
                            </tr>
                            <tr>
                                <th scope="row">Card Number</th>
                                <td class="card-number"></td>
                            </tr>
                            <tr>
                                <th scope="row">Card Expire</th>
                                <td class="card-expire"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p>Billing info:</p>
                                    <table class="w-100">
                                        <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($payableInvoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice['InvoiceNumber'] }}</td>
                                                <td>${{ str_replace(',', '', $invoice['InvoiceBalance'] ?? 0) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>Total:</td>
                                            <td>=${{ $accountPayableAmount }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Process with this card</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <button type="button" class="d-none btn btn-primary" data-toggle="modal" data-target="#processPayment">
        Launch demo modal
    </button>
    <div id="modelBackdrop"></div>
</div>
