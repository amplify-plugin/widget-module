<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            @if($invoice != null)
                <div class="d-flex justify-content-between border-bottom pb-3">
                    <a href="{{ route('frontend.invoices.index') }}" class="font-weight-bold btn btn-link-info btn-sm">
                        <i class="icon-arrow-left mr-2"></i>
                        Back to Invoice Summary
                    </a>
                    <div class="d-flex justify-content-end">
                        @if ($invoice['InvoiceNumber'] != null)
                            <a href="{{ route('frontend.invoices.document.download', ['I', $invoice['InvoiceNumber']])  }}" class="btn btn-sm btn-success mr-2">
                                <i class="icon-download mr-2"></i>
                                Invoice PDF
                            </a>
                        @endif

                        @if ($showSignedShipperBtn && $invoice['OrderNumber'] != null)
                            <a href="{{ route('frontend.invoices.document.download', ['P', $invoice['OrderNumber']])  }}" class="btn btn-sm btn-primary mr-2">
                                <i class="icon-download mr-2"></i>
                                Signed Shipper PDF
                            </a>
                        @endif

                        @if ($showTrackingButton && $invoice['OrderNumber'] != null)
                            <a
                                href="{{ route('frontend.invoices.tracking.invoice', $invoice['InvoiceNumber'])  }}"
                                class="btn btn-sm btn-primary mr-2"
                                target="_blank"
                            >
                                <i class="icon-download mr-2"></i>
                                Track Shipment
                            </a>
                        @endif
                    </div>
                </div>
                <div class="d-flex justify-content-start py-3 border-bottom">
                    <p>
                        {{ $invoiceNumberTitle }}: {{ $invoice['OrderSuffix'] ?? '' }}<br>
                        Date: {{ $invoice['EntryDate'] ?? '' }}
                    </p>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row py-3">
                            <div class="col-md-3 border-right">
                                Bill To: <br>
                                {{ $invoice['CustomerNumber'] ?? '' }}
                            </div>
                            <div class="col-md-3 border-right">
                                {{ $invoice['CustomerName'] ?? '' }} <br>
                                {{ $invoice['CustomerAddress1'] ?? '' }} <br>
                                {{ $invoice['ShipToCity'] ?? '' }} {{ $invoice['ShipToState'] ?? '' }} {{ $invoice['BillToZipCode'] ?? '' }}
                            </div>
                            <div class="col-md-3 border-right">
                                Ship To: <br>
                                {{ $invoice['ShipToNumber'] ?? '' }}
                            </div>
                            <div class="col-md-3">
                                {{ $invoice['CustomerName'] ?? '' }} <br>
                                {{ $invoice['CustomerAddress1'] ?? '' }} <br>
                                {{ $invoice['ShipToCity'] ?? '' }} {{ $invoice['ShipToState'] ?? '' }} {{ $invoice['BillToZipCode'] ?? '' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-bordered mb-0">
                            <tr>
                                <th>Reference #</th>
                                <th>Shipped</th>
                                <th>Terms</th>
                                <th>Tax Code</th>
                                <th>Doc #</th>
                                <th>Wh</th>
                                <th>Freight</th>
                                <th>Ship Via</th>
                            </tr>
                            <tr>
                                <td>{{ $invoice['CustomerPurchaseOrdernumber'] ?? '' }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $invoice['OrderNumber'] ?? '' }}</td>
                                <td>{{ $invoice['WarehouseID'] ?? '' }}</td>
                                <td></td>
                                <td>{{ $invoice['CarrierCode'] ?? '' }}</td>
                            </tr>
                        </table>
                        <table class="table table-bordered mb-0">
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Ordered</th>
                                <th>Shipped</th>
                                <th>Backordered</th>
                                <th>Unit Price</th>
                                <th>Extension</th>
                            </tr>
                            <tbody>
                            @forelse (($invoice['OrderDetail'] ?? []) as $invoiceItem)
                                <tr>
                                    <td>{{ $invoiceItem['ItemNumber'] ?? '' }}</td>
                                    <td>{{ $invoiceItem['ItemDescription1'] ?? '' }}</td>
                                    <td>{{ $invoiceItem['QuantityOrdered'] ?? '' }}</td>
                                    <td>{{ $invoiceItem['QuantityShipped'] ?? '' }}</td>
                                    <td>{{ $invoiceItem['QuantityBackordered'] ?? '' }}</td>
                                    <td>{{ $invoiceItem['ActualSellPrice'] ?? '' }}</td>
                                    <td>{{ price_format($invoiceItem['TotalLineAmount'] ?? '') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-danger">
                                            <strong>{{ __('No Details Found!') }}</strong>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="6">Merchandise</th>
                                <td>{{ currency_format($invoice->ItemSalesAmount, null, true) }}</td>
                            </tr>
                            <tr>
                                <th colspan="6">Misc</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="6">Discount</th>
                                <td>{{ currency_format($invoice->DiscountAmountTrading, null, true) }}</td>
                            </tr>
                            <tr>
                                <th colspan="6">Tax</th>
                                <td>{{ currency_format($invoice->SalesTaxAmount, null, true) }}</td>
                            </tr>
                            <tr>
                                <th colspan="6">Freight</th>
                                <td>{{ currency_format($invoice->FreightAmount, null, true) }}</td>
                            </tr>
                            @if(isset($invoice->HazMatCharge) &&!empty($invoice->HazMatCharge))
                                <tr>
                                    <th colspan="6">HazMat Charge</th>
                                    <td>{{ currency_format($invoice->HazMatCharge, null, true) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th colspan="6">Total Due</th>
                                <td>{{ currency_format($invoice->InvoiceAmount, null, true) }}</td>
                            </tr>
                            <tr>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @else
                <div class="alert alert-danger text-center">
                    <b>There are no details available for this invoice.</b>
                </div>
            @endif
        </div>
    </div>
</div>
