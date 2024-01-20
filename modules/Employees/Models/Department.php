<?php

namespace Modules\Employees\Models;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Modules\Employees\Builders\Department as Builder;
use Modules\Employees\Scopes\Department as Scope;
use Modules\Employees\Relations\HasMany\Department as HasMany;

class Department extends Model
{
    use HasFactory, Cloneable;

    protected $table = 'employees_departments';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'manager_id',
        'parent_id',
        'description',
        'enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    protected $sortable = ['name', 'enabled'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new Scope);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Modules\Employees\Builders\Department
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Instantiate a new HasMany relationship.
     *
     * @param  EloquentBuilder  $query
     * @param  EloquentModel  $parent
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return HasMany
     */
    protected function newHasMany(EloquentBuilder $query, EloquentModel $parent, $foreignKey, $localKey)
    {
        return new HasMany($query, $parent, $foreignKey, $localKey);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveRouteBindingQuery($this, $value, $field)
            ->withoutGlobalScope(Scope::class)
            ->getWithoutChildren()
            ->first();
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'parent_id')->withSubDepartment();
    }

    public function sub_departments()
    {
        return $this->hasMany(Department::class, 'parent_id')->withSubDepartment()->with('departments')->orderBy('name');
    }

    public function employees()
    {
        return $this->hasMany('Modules\Employees\Models\Employee');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'manager_id', 'id');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }

    /**
     * Scope gets only parent departments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSubDepartment($query)
    {
        return $query->withoutGlobalScope(new Scope);
    }

            /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('employees.settings.departments.edit', $this->id),
            'permission' => 'update-employees-employees',
        ];

        $actions[] = [
            'type' => 'delete',
            'title' => trans_choice('employees::general.departments', 1),
            'icon' => 'delete',
            'route' => 'employees.settings.departments.destroy',
            'permission' => 'delete-employees-employees',
            'model' => $this,
        ];

        return $actions;
    }

    public static function newFactory(): Factory
    {
        return \Modules\Employees\Database\Factories\Department::new();
    }
}
