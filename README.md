<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Overview
- MariaDB was used as a Database.
- Currently only Czech holidays are considered and seeded, but it would be no problem to add more because of the `country` column in `Holidays` table. Thus we could use this column for different nations or even use all holidays. Weekend days are just class constants.
- Eastern holidays for Czechia are calculted by equations provided by: https://cs.wikipedia.org/wiki/V%C3%BDpo%C4%8Det_data_Velikonoc (only for years 2000-2099)
- PHPUnit tests were used to test functionality of Task class.
- Postman collection is saved in root directory in: `OGSoft.postman_collection.json`

## Setup
- We need a MySQL database, create there a new database
- Clone this repository `$ git clone https://github.com/RostislavKral/ogsoft.git`
- `$ composer install`
- `$ cp .env.example .env`
- Configure your `DB_CONNECTION=`
- `$ php artisan key:generate`
- `$ php artisan migrate`
- `$ php artisan db:seed`
- `$ php artisan serve`
- `$ php artisan test` to test the functionality


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
