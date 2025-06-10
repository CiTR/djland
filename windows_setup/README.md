This is the guide for a native windows development setup (no wsl2 needed).
Note that this is a stopgap until docker containers and docker compose is setup.

# Download the following

Laragon (WAMP Server)
https://laragon.org/download/

PHP 7
https://windows.php.net/downloads/releases/php-7.4.33-nts-Win32-vc15-x64.zip

PHP 8
https://windows.php.net/downloads/releases/php-8.4.8-nts-Win32-vs17-x64.zip

Composer
https://getcomposer.org/Composer-Setup.exe

VSCode
https://code.visualstudio.com/download


# Installation

## Install Laragon C:\laragon

This is a standard installer. Make sure that you have it auto-generate virtual hosts for folders in C:\laragon\www (it's an option)

### Swap to use PHP7
Extract PHP 7.4.33 to C:\laragon\bin\php\php-7.4.33-nts-Win32-vc15-x64
Extract PHP 8.4.8 to C:\laragon\bin\php\php-8.4.8-nts-Win32-vs17-x64

Since we're currently on PHP 7, update "C:\laragon\usr\profile\default.ini" [php] section to 
```
[php]
; Version=php-8.3.16-Win32-vs16-x64
Version=php-7.4.33-nts-Win32-vc15-x64
```

## Install Composer

It's an exe installer so just double click to run.
Select C:\laragon\bin\php\php-7.4.33-nts-Win32-vc15-x64 as your PHP version (you can re-install composer and pick PHP8 later if you want)

Verify the install added it to the path correctly

- Hit Windows, type cmd, hit enter (terminal opens)
```
    composer --version
```
Ensure that it lists the PHP version correctly
```
composer --version
Composer version 2.8.9 2025-05-13 14:01:37
PHP version 7.4.33 (C:\laragon\bin\php\php-7.4.33-nts-Win32-vc15-x64\php.exe)
Run the "diagnose" command to get more detailed diagnostics output.
```

## Install VSCode

It's an exe installer, so just double click to run

# Setup of DJLand

## Clone the repository
- open VSCode
- select "clone existing respository"
- accept the prompt to install the git plugin
- follow the instructions https://code.visualstudio.com/docs/sourcecontrol/github#_authenticating-with-an-existing-repository

## Start Up Laragon

- Start up laragon
- Start Apache & Mysql

## Create a new database in MariaDB

- In Laragon select "Menu" > MySQL > change root password.
- Set the password and copy keep it somewhere
- In Laragon select "Menu" > MySQL > HeidiSQL
- Create a new connection with 'root' and the password you just set.
- Right click on "Laragon.MySQL" in the database listing on left
- Select "create new database"
- Create djland database
- Select "tools" in top nav and go to User manager
- Add a new user -> User name = djland, and set password
- Select "Add object" in the "Allow access to" section and click the djland database you just made. Select global priveleges for that database.

## Some pre-setup init

- copy the /app/api2/.env.sample to /app/api2/.env
- edit .env DB_USERNAME=djland, and DB_PASSWORD accordingly
- open a terminal (windows, cmd, enter)
- cd to the /app/api2 directory
- run `composer install`
- run `php artisan key:generate`

## Configure the virtualhost for Laragon

- In your filesystem Create a directory called C:\laragon\www\djland (this is just a dir to auto-make a virtualhost file and be lazy, remember that checkbox you clicked in laragon install?)
- Select "Menu" in the top left, and go to Apache > sites-enabled > auto.djland.test.conf
- Edit the path in DocumentRoot to "C:\path\to\git\djland\app"
- Edit the Directory to the parent directory "C:\path\to\git\djland"

Your virtualhost file should look like so:
```
<VirtualHost *:80> 
    DocumentRoot "C:\path\to\git\djland\app"
    ServerName djland.test
    ServerAlias *.djland.test
    <Directory "C:\path\to\git\djland">
        AllowOverride All
        Require all granted
    </Directory>
    <Directory "C:\path\to\git\djland_podcaster">
        AllowOverride All
        Require all granted
    </Directory>
    <Directory "C:\path\to\git\djland_xmls">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# If you want to use SSL, enable it by going to Menu > Apache > SSL > Enabled
```
- Click "reload" in the laragon UI next to apache
- Click "Menu" and go to www > djland
- It should redirect you to initial setup.
- Fill out the values there (including your database, and path to xmls/podcasts)
- Eventually you can click "go to djland"
- Default login is Admin / 1234







