<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal',
            'Kasir',
            'Metode Pembayaran',
            'Subtotal',
            'Pajak',
            'Diskon',
            'Grand Total',
        ];
    }

    public function map($trx): array
    {
        return [
            '#' . $trx->id,
            $trx->created_at->format('d/m/Y H:i'),
            $trx->kasir->name,
            strtoupper($trx->payment_method),
            $trx->total_price,
            $trx->tax_amount,
            $trx->discount_amount,
            $trx->grand_total,
        ];
    }
}
