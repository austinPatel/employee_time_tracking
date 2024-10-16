<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Employee Timelog Tracking Application
An employee time tracking application that allows employees to log their work hours against specific departments, projects, and subprojects. Managers can view and manage these logs. The application use Laravel for the backend, Jetstream for authentication, Livewire for reactive components, Filament for form management, Tailwind CSS for styling, Eloquent for ORM, MySQL for the database and Pest of unit testing.


### Application Setup 

- **git clone https://github.com/austinPatel/employee_time_tracking.git**
- **composer install**
- **Change .env configuration for database**
- **Php artisan migrate**
- **npm install && npm run dev**
- **php artisan serve**
- **Employee register - default role has been set as 'Employee'**
- **Manager register - change role 'Manager'**

### Add Dummy Data
php artisan tinker
- **Department::factory()->count(3)->create();**
- **Project::factory()->count(3)->create();**
- **SubProject::factory()->count(5)->create();**

### Test Cases
- **Employee Add Log hours -**
-vendor/bin/pest tests/Feature/EmployeeLogHoursTest.php

- **Check Employee can view Log hours**
-vendor/bin/pest tests/Feature/EmployeeViewLogsTest.php

execute below command for all test cases

-vendor/bin/pest

### Filament Admin panel 
- **Create resources as below -**
- **php artisan make:filament-resource Department-**
- **php artisan make:filament-resource Project-**
- **php artisan make:filament-resource SubProject-**
- **php artisan make:filament-resource TimeLog-**





