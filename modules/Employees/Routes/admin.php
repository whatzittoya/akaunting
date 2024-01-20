<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'employees' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('employees', function () {
    Route::get('employees/{employee}/duplicate', 'Employees@duplicate')->name('employees.duplicate');
    Route::post('employees/import', 'Employees@import')->name('employees.import');
    Route::get('employees/export', 'Employees@export')->name('employees.export');
    Route::get('employees/{employee}/enable', 'Employees@enable')->name('employees.enable');
    Route::get('employees/{employee}/disable', 'Employees@disable')->name('employees.disable');
    Route::resource('employees', 'Employees')->middleware(['dropzone']);

    Route::group(['as' => 'modals.', 'prefix' => 'modals'], function () {
        Route::resource('departments', 'Modals\Departments');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', 'Settings\Settings@edit')->name('edit');
        Route::post('/', 'Settings\Settings@update')->name('update');

        Route::post('departments/import', 'Settings\Departments@import')->name('departments.import');
        Route::get('departments/export', 'Settings\Departments@export')->name('departments.export');
        Route::resource('departments', 'Settings\Departments');
    });

    //this route is added for empty page create button
    Route::get('departments/create', 'Settings\Departments@create')->name('departments.create');
});
