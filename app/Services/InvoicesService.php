<?php
namespace App\Services;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoicesService {
    public function generateInvoice($payment) {
        $customer = new Buyer([
            'name'          => '$payment->user->name',
            'custom_fields' => [
                'email' => '$payment->user->email',
            ],
        ]);
//        $payment->total
        $item = (new InvoiceItem())->title('Service 1')->pricePerUnit(2);

        $invoice = Invoice::make()
            ->buyer($customer)
//            ->discountByPercent(10)
//            ->taxRate(15)
//            ->shipping(1.99)
            ->addItem($item);

        return $invoice->stream();
    }
}
