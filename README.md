<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About this project
So, After creating the table in the database, run the Laravel app.
Product creation:
 To create a product through API, create products (two categories have already been created in the categories section)
Filter section:
To get the list of discounted products, set the discount parameter = true, otherwise non-discounted products will be displayed
To apply the filter in the price section, you can specify the maximum and minimum value for the filter.
If there is a discount in the APA request sent (with correct or false values), the list with or without discount will be received in the mentioned query, and if there is no discount field in the APA request sent, all the products Both with discount and without discount are displayed.

API
Postman's file is attached
The postman file has two sections: product creation and product list filter
