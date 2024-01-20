<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeesV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('employees_positions', 'employees_departments');

        Schema::table('employees_departments', function (Blueprint $table) {
            $table->integer('manager_id')->nullable()->after('name');
            $table->integer('parent_id')->nullable()->after('manager_id');
            $table->text('description')->nullable()->after('parent_id');
        });

        Schema::table('employees_employees', function (Blueprint $table) {
            $table->renameColumn('position_id', 'department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('employees_departments', 'employees_positions');

        Schema::table('employees_positions', function (Blueprint $table) {
            $table->dropColumn('manager_id');
            $table->dropColumn('parent_id');
            $table->dropColumn('desription');
        });

        Schema::table('employees_employees', function (Blueprint $table) {
            $table->renameColumn('department_id', 'position_id');
        });
    }
}
