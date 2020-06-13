# Online Peer Assessment Tool for Group Assignments [![Build Status](https://travis-ci.com/Damian96/peer-assesment-app.svg?branch=master)](https://travis-ci.com/Damian96/peer-assesment-app)
2019-36 - Online Peer Assessment Tool for Group Assignments<br>
***a.k.a Web Peer Evaluation System (WPES)***

# Technologies
* [Laravel](https://github.com/laravel/laravel)
* [Webpack](https://webpack.js.org/)
* [PHPUnit](https://phpunit.cn)

# Installation

## Environment Setup
* [XAMPP stack](https://www.apachefriends.org/index.html)
* [PHPv7.3.18](https://www.php.net/downloads.php#v7.3.18)
* [Composer](https://getcomposer.org/download/)
* [Node Package Manager](https://www.npmjs.com/get-npm)
* Grab the `.env` [file](https://gist.github.com/Damian96/ad15a3315494cf0d912ef833b7d29ff9)

## Dependencies
* `$ composer install`
* `$ npm install`
* `$ chmod 775 storage/ bootstrap/ -R`
* PHP extensions: [mysqli](https://www.php.net/manual/en/mysqli.setup.php), [curl](https://www.php.net/manual/en/curl.setup.php), [mbstring](https://www.php.net/manual/en/mbstring.setup.php)
* MySQL Client 7.4.4
* Apache v2.4
* Create a database named `wpesDB` with collation `utf8mb4_general_ci`

## Preparing
* `$ php artisan key:generate`
* `$ php artisan migrate`
* `$ php artisan db:seed`
* `$ npm run prod`
* `$ composer cache:store`
* `$ php artisan serve`
<br>

As an optional step, you can also conduct the **tests** of the project via:
* `$ composer test`
<br>

The project now is installed and ready to use at [http://127.0.0.1:8000](http://127.0.0.1:8000).
You can login with the following **credentials (username:password)**:
* Admin: `dgiankakis:everse2309`
* Instructor: `istamatopoulou:123`
* Student: `pgrammatikopoulos:123` <!--(password is the same for any other student account) -->
<br>

Happy Assessing!

# License
The software is licensed under the [GNU General Public License v3.0](https://github.com/Damian96/peer-assesment-app/blob/master/LICENSE)
