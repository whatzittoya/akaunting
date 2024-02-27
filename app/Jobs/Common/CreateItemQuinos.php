<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Common\CreateItemTaxes;
use App\Models\Common\Item;
use App\Models\Setting\Category;
use Illuminate\Support\Js;

class CreateItemQuinos extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Item
    {
        \DB::transaction(function () {
            $item_type = Category::ITEM_TYPE;
            $category_arr = [
                'name' => $this->request->category_name,
                'type' => $item_type,
                'enabled' => 1,
                'created_from' => $this->request->created_from,
                'created_by' => $this->request->created_by,
                'company_id' => company_id(),
                'color' => ''

            ];

            $category = Category::firstOrCreate($category_arr);

            $this->request->merge(['category_id' => $category->id, 'company_id' => company_id()]);
            $item = new Item;
            $this->model = $item->createOrUpdate($this->request->source_id, $this->request->company_id, $this->request->all());

            // $this->model = Item::create($this->request->all());

        });

        return $this->model;
    }
}
