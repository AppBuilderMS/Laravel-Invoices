<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

//class ArchivedPaidInvoicesExport implements FromCollection
//{
//    /**
//    * @return \Illuminate\Support\Collection
//    */
//    public function collection()
//    {
//        return Invoice::all();
//    }
//}

class ArchivedPaidInvoicesExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Invoice::onlyTrashed()->where('value_status', 1);
    }

    public function headings(): array
    {
        $lang = app()->getLocale();
        if($lang == 'en') {
            return [
                'Invoice Number',
                'Invoice Date',
                'Due Date',
                'Department',
                'Product',
                'Collection Amount',
                'Commission Rate',
                'Commission Amount',
                'Discount Rate',
                'Discount',
                'VAT Rate',
                'VAT Value',
                'Sub Total',
                'Total',
                'Status',
                'Notes',
                'Payment Amount',
                'Partial Payment Date',
                'Remaining Amount',
                'Total Payment Date',
                'Archived Date'
            ];
        }

        if($lang == 'ar') {
            return [
                'رقم الفاتورة',
                'تاريخ الفاتورة',
                'تاريخ الإستحقاق',
                'القسم',
                'المنتج',
                'مبلغ التحصيل',
                'نسبة العمولة',
                'قيمة العمولة',
                'نسبة الخصم',
                'قيمة الخصم',
                'نسبة ض.ق.م',
                'قيمة ض.ق.م',
                'الإجمالى قبل ض.ق.م',
                'الإجمالى بعد ض.ق.م',
                'الحالة',
                'ملاحظات',
                'المبلغ المسدد',
                'تاريخ السداد الجزئى',
                'المبلغ المتبقى',
                'تاريخ السداد الكلى',
                'تاريخ الأرشفة'
            ];
        }

    }

    /**
     * @var Invoice $invoice
     */
    public function map($invoice): array
    {
        $lang = app()->getLocale();
        if($lang == 'ar'){
            return [
                $invoice->invoice_number,
                $invoice->invoice_date,
                $invoice->due_date,
                $invoice->department->dep_name_ar,
                $invoice->product->product_name_ar,
                $invoice->collection_amount,
                $invoice->commission_rate,
                $invoice->commission_amount,
                $invoice->discount_rate,
                $invoice->discount,
                $invoice->rate_vat,
                $invoice->value_vat,
                $invoice->sub_total,
                $invoice->total,
                $invoice->status_ar,
                $invoice->notes_ar,
                $invoice->payment_amount,
                $invoice->partial_payment_date,
                $invoice->remaining_amount,
                $invoice->total_payment_date,
                $invoice->deleted_at->format('Y-m-d'),
            ];
        }
        if($lang == 'en'){
            return [
                $invoice->invoice_number,
                $invoice->invoice_date,
                $invoice->due_date,
                $invoice->department->dep_name_en,
                $invoice->product->product_name_en,
                $invoice->collection_amount,
                $invoice->commission_rate,
                $invoice->commission_amount,
                $invoice->discount_rate,
                $invoice->discount,
                $invoice->rate_vat,
                $invoice->value_vat,
                $invoice->sub_total,
                $invoice->total,
                $invoice->status_en,
                $invoice->notes_en,
                $invoice->payment_amount,
                $invoice->partial_payment_date,
                $invoice->remaining_amount,
                $invoice->total_payment_date,
                $invoice->deleted_at->format('Y-m-d'),
            ];
        }
    }
}
