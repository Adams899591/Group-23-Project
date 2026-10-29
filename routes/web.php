<?php

use App\Livewire\Applicants;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;

Route::get('/', function () {
    return view('welcome');
});



Route::prefix("fudma")->group(function(){
    Route::get('/dashboard', Dashboard::class)->name("dashboard");
    Route::get('/applicants', Applicants::class)->name("applicants");
});



    //   ['label' => 'Dashboard',         'icon' => 'home',     'href' => 'dashboard',         'pattern' => 'dashboard*'],
    //         ['label' => 'Applicants',        'icon' => 'users',    'href' => '/applicants',        'pattern' => 'applicants*'],
    //         ['label' => 'Applications',      'icon' => 'document', 'href' => '/applications',      'pattern' => 'applications*'],
    //         ['label' => 'Programmes',        'icon' => 'cap',      'href' => '/programmes',        'pattern' => 'programmes*'],
    //         ['label' => 'Departments',       'icon' => 'library',  'href' => '/departments',       'pattern' => 'departments*'],
    //         ['label' => 'Faculties',         'icon' => 'office',   'href' => '/faculties',         'pattern' => 'faculties*'],
    //         ['label' => 'Academic Sessions', 'icon' => 'calendar', 'href' => '/academic-sessions', 'pattern' => 'academic-sessions*'],
    //         ['label' => 'Reports',           'icon' => 'chart',    'href' => '/reports',           'pattern' => 'reports*'],
    //         ['label' => 'Settings',          'icon' => 'settings', 'href' => '/settings', 