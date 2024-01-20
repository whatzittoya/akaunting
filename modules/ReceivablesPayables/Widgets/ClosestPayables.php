<?php

namespace Modules\ReceivablesPayables\Widgets;

use App\Abstracts\Widget;
use App\Models\Document\Document;
use App\Utilities\Date;

class ClosestPayables extends Widget
{
    public $default_name = 'receivables-payables::general.closest_payables';

    public function show()
    {
        $model = Document::bill()->accrued()->notPaid()->with('contact')->where('due_at', '<=', Date::today()->toDateTimeString())->orderBy('due_at', 'desc')->take(5);

        $bills = $this->applyFilters($model, ['date_field' => 'due_at'])->get()->transform(function ($bill) {
            $payments = 0;

            if ($bill->status == 'partial') {
                $bill->load('transactions');

                foreach ($bill->transactions as $transaction) {
                    $payments += $transaction->amount;
                }
            }

            $bill->amount = $bill->amount - $payments;

            return $bill;
        })->all();

        return $this->view('receivables-payables::closest_payables', [
            'bills' => $bills,
        ]);
    }
}
