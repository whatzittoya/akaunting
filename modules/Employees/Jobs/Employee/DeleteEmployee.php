<?php

namespace Modules\Employees\Jobs\Employee;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use App\Jobs\Common\DeleteContact;
use Modules\Employees\Events\DetermineRelationships;

class DeleteEmployee extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->model->delete();

            $this->dispatch(new DeleteContact($this->model->contact));
        });

        return true;
    }

    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->model->contact->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    private function getRelationships(): array
    {
        $rel = new class {
            public $relationships = [];
        };

        event(new DetermineRelationships($rel));

        return $this->countRelationships($this->model, $rel->relationships);
    }
}
