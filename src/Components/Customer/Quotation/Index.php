<?php

namespace Amplify\Widget\Components\Customer\Quotation;

use Amplify\ErpApi\ErpApiService;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\ErpApi\Wrappers\Quotation;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class Index extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $quoteNumberLabel = '',
        public string $quoteTypeLabel = '',
        public string $quoteToLabel = '',
        public string $entryDateLabel = '',
        public string $expiredDateLabel = '',
        public string $quoteAmountLabel = '',
        public string $orderValueLabel = '',
        public string $withShipping = '',
        public string $purchaseOrderNumberLabel = '',
        public string $wharehouseLabel = ''
    ) {
        parent::__construct();

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer(true)->can('quote.view');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $to = request('created_end_date', now()->format('Y-m-d'));
        $from = request('created_start_date', now()->subDays(7)->format('Y-m-d'));

        $quotations = ErpApi::getQuotationList([
            'start_date' => $from,
            'end_date' => $to,
            'transaction_types' => ErpApiService::TRANSACTION_TYPES_QUOTE,
        ]);

        $columns = [
            'QuoteNumber' => (strlen($this->quoteNumberLabel) != 0),
            'QuoteTo' => (strlen($this->quoteToLabel) != 0),
            'QuoteType' => (strlen($this->quoteTypeLabel) != 0),
            'EntryDate' => (strlen($this->entryDateLabel) != 0),
            'ExpirationDate' => (strlen($this->expiredDateLabel) != 0),
            'QuoteAmount' => (strlen($this->quoteAmountLabel) != 0),
            'TotalOrderValue' => (strlen($this->orderValueLabel) != 0),
            'PurchaseOrderNumber' => (strlen($this->purchaseOrderNumberLabel) != 0),
            'Wharehouse' => (strlen($this->wharehouseLabel) != 0),
        ];

        return view(
            'widget::customer.quotation.index',
            compact('quotations', 'columns')
        );
    }

    public function getQuoteDisplayNumber(Quotation $quotation): string
    {
        if (! empty($quotation['Suffix'])) {
            return $quotation['QuoteNumber'].'-'.$quotation['Suffix'];
        }

        if (! empty($quotation['QuoteNumber'])) {
            return $quotation['QuoteNumber'];
        }

        return '';
    }

    public function getAdditionalQueryParam(Quotation $quotation): string
    {
        $params = [];

        if (! empty($this->withShipping)) {
            $params['ship_vias'] = 1;
        }

        if (! empty($quotation['Suffix'])) {
            $params['suffix'] = $quotation['Suffix'];
        }

        return ! empty($params) ? '?'.http_build_query($params) : '';
    }
}
