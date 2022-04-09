# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

## Tahap instalasi
### Clonning repo [ini](https://github.com/ibnuhamdani11/rest-api-lumen-jwt)
bisa dengan command `git clone https://github.com/ibnuhamdani11/rest-api-lumen-jwt name_project`
atau dengan manual download ke repo tersebut

### Setup DB
copy dan ubah `.env.example` menjadi `.env` kemudian isi dengan data seperti dibawah ini
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1  
DB_PORT=3306 // sesuai port
DB_DATABASE=rest_api_lumen // sesuai nama DB
DB_USERNAME=root // sesuai user
DB_PASSWORD= secret // sesuai password
```
untuk database bisa menggunakan perintah `php artisan migrate` atau dengan restore/import data sql nya, ada di [url ini](https://github.com/ibnuhamdani11/rest-api-lumen-jwt/blob/master/rest_api_lumen.sql)

### jalankan perintah `composer install` untuk instalasi package
detail package yang dipakai adalah
```
composer require chuckrincon/lumen-config-discover
composer require tymon/jwt-auth
composer require guzzlehttp/guzzle
```

### dokumentasi rest client
untuk dokumentasi endpoint dan payloadnya bisa lihat di file rest-client.http atau bisa dengan klik [ url ini](https://github.com/ibnuhamdani11/rest-api-lumen-jwt/blob/master/rest-client.http)