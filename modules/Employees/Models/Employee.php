<?php

namespace Modules\Employees\Models;

use App\Traits\Media;
use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Modules\Employees\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use Cloneable, HasFactory, Media;

    protected $table = 'employees_employees';

    protected $appends = ['attachment', 'email', 'name'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'contact_id',
        'birth_day',
        'gender',
        'department_id',
        'amount',
        'salary_type',
        'hired_at',
        'bank_account_number',
    ];

    protected $casts = [
        'amount' => 'double',
    ];

    public $sortable = ['contact.name', 'contact.name', 'department.name', 'hired_at'];

    /**
     * Cloneable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['contact'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo('App\Models\Common\Contact');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withSubDepartment();
    }

    public function scopeEnabled($query): Builder
    {
        return $query
            ->join('contacts', 'employees_employees.contact_id', '=', 'contacts.id')
            ->where('contacts.enabled', 1)
            ->select('employees_employees.*');
    }

    public static function getAvailableGenders(): array
    {
        return [
            'male'   => trans('employees::employees.male'),
            'female' => trans('employees::employees.female'),
            'other'  => trans('employees::employees.other')
        ];
    }

    public function getNameAttribute()
    {
        return !empty($this->contact->name) ? $this->contact->name : trans('general.na');
    }

    public function getEmailAttribute()
    {
        return $this->contact->email;
    }

    public function getAttachmentAttribute($value = null)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->all();
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
            'title' => trans('general.show'),
            'icon' => 'visibility',
            'url' => route('employees.employees.show', $this->id),
            'permission' => 'read-employees-employees',
        ];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('employees.employees.edit', $this->id),
            'permission' => 'update-employees-employees',
        ];

        $actions[] = [
            'title' => trans('general.duplicate'),
            'icon' => 'file_copy',
            'url' => route('employees.employees.duplicate', $this->id),
            'permission' => 'create-employees-employees',
        ];

        $actions[] = [
            'type' => 'delete',
            'title' => trans_choice('employees::general.employees', 1),
            'icon' => 'delete',
            'route' => 'employees.employees.destroy',
            'permission' => 'delete-employees-employees',
            'model' => $this,
        ];

        return $actions;
    }

    public static function newFactory(): Factory
    {
        return \Modules\Employees\Database\Factories\Employee::new();
    }
}
