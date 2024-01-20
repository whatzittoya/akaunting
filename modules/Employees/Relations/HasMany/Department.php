<?php

namespace Modules\Employees\Relations\HasMany;


use Modules\Employees\Models\Department as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends HasMany
{
    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        if (! is_null($this->getParentKey()) && $this->related instanceof Model) {
            return $this->query->getWithoutChildren();
        }

        return ! is_null($this->getParentKey())
                ? $this->query->get()
                : $this->related->newCollection();
    }
}
