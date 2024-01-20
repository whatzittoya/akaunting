<?php

namespace Modules\DoubleEntry\Observers\Setting;

use App\Abstracts\Observer;
use App\Models\Setting\Tax as Model;
use App\Traits\Modules;
use Modules\DoubleEntry\Models\Account as Coa;
use Modules\DoubleEntry\Models\AccountTax;
use Modules\DoubleEntry\Traits\Accounts;

class Tax extends Observer
{
    use Accounts, Modules;

    /**
     * Listen to the created event.
     *
     * @param  Model  $tax
     * @return void
     */
    public function created(Model $tax)
    {
        if ($this->moduleIsDisabled('double-entry')) {
            return;
        }

        if (strpos($tax->name, 'chart-of-accounts')) {
            $tax->name = str_replace('chart-of-accounts', '', $tax->name);
            $tax->save();

            return;
        }

        $coa = Coa::create([
            'company_id' => $tax->company_id,
            'type_id' => setting('double-entry.types_tax', 17),
            'code' => $this->getNextAccountCode(),
            'name' => $tax->name,
        ]);

        AccountTax::create([
            'company_id' => $tax->company_id,
            'account_id' => $coa->id,
            'tax_id' => $tax->id,
        ]);
    }

    /**
     * Listen to the updated event.
     *
     * @param  Model  $tax
     * @return void
     */
    public function updated(Model $tax)
    {
        $account_tax = AccountTax::where('tax_id', $tax->id)->first();

        if (!$account_tax) {
            return;
        }

        $coa = $account_tax->account;

        $coa->update([
            'name' => $tax->name,
            'code' => $coa->code,
            'type_id' => $coa->type_id,
            'enabled' => $tax->enabled,
        ]);
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model  $tax
     * @return void
     */
    public function deleted(Model $tax)
    {
        $account_tax = AccountTax::where('tax_id', $tax->id)->first();

        if (!$account_tax) {
            return;
        }

        $account_tax->account->delete();
    }
}
