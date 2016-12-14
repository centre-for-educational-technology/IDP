# ELU application

Version 1.0

## Requirements

* PHP 7.0 and higher

* SSH enabled on server

* Composer

* SimpleSAML set and configured
  
* Supports databases
  * MySQL (Tested on MySQL 5.5)
  * Postgres
  * SQLite
  * SQL Server
  
  
## Installation 

### 1. Install dependencies

Run `composer install` command in the root folder of the project.

### 2. Edit the configuration file

`.env.example`  and change name to `.env`

### 3. Import the database scheme

Run `php artisan migrate` to create database tables

Run `php artisan db:seed` to populate pages content

### 4. Make sure `.htaccess` file in `public` directory and server config files are configured correctly

## Post-installation procedures

Register a new user.

## Special cases 

### Recreate database structure (NB! THIS WILL ERASE EXISTING DATA)

Run `php artisan migrate:refresh --seed` to re-create the entire database