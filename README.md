# Online Peer Assessment Tool for Group Assignments [![Build Status](https://travis-ci.com/Damian96/peer-assesment-app.svg?branch=master)](https://travis-ci.com/Damian96/peer-assesment-app)
2019-36 - Online Peer Assessment Tool for Group Assignments<br>
***a.k.a Web Peer Evaluation System (WPES)***

# Technologies
* [Laravel](https://github.com/laravel/laravel)
* [Webpack](https://webpack.js.org/)

# Installation
First install the requirements in your personal computer (all requirements are installable in every platform):
* [PHPv7.3.18](https://www.php.net/downloads.php#v7.3.18)
* [Composer](https://getcomposer.org/download/)
* [Node Package Manager](https://www.npmjs.com/get-npm)
* [XAMPP stack](https://www.apachefriends.org/index.html)

<!-- * Next install grab the project's zip file and unzip it into the `htdocs` folder of XAMPP, in a folder named `wpes`. You can navigate into the htdocs folder through XAMPP's GUI explorer button. as shown in the screenshot below: -->
<!-- * Open the Apache configuration `httpd-xampp.conf` through XAMPP's config button and append the following snippet into the configuration file:
    ```
    <VirtualHost *:80>
    DocumentRoot "/path/to/wpes/public"
    ServerName wpes.test
    <Directory "/path/to/wpes/public">
        DirectoryIndex index.php index.html
        AllowOverride All
        Options +FollowSymLinks +Indexes
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>
    </VirtualHost>
    ``` -->
* Rename the project's folder to: `wpes`. 
* Download the `.env` file from [here](https://gist.github.com/Damian96/ad15a3315494cf0d912ef833b7d29ff9) into the root folder of the project.

From this point onwards you will need a PHP binary to complete the installation, so I suggest you add the PHP binary to your system's PATH. <br>
If you don't wish to add it, you can open the folder of XAMPP's MySQL installation, with **XAMPP -> PHP Config -> <Browse> (PHP)** as shown in the figure below: 
* ![](https://user-images.githubusercontent.com/19414954/82142395-bdef5c00-9844-11ea-95e0-607f51e6cbd1.JPG)
There you can find the path to the PHP binary, and copy it so you can use it more easily.
Now execute the list of commands below, while in the `wpes` directory:
* `$ composer install`
* `$ npm install`
* ***If you are on Linux***, execute the following command into the `wpes` directory: `$ chmod 775 storage/ bootstrap/ -R` 
* `$ /path/to/php-binary artisan key:generate`
* Open the `phpMyAdmin` webpage from XAMPP's GUI button MySQL -> Admin.
    * Create a new database named `wpesDB`, with encoding `utf8mb4_general_ci` in the webpage's form, as shown in the screenshot below:
    ![phpMyAdmin-createDatabase](https://user-images.githubusercontent.com/19414954/82143004-5687db00-9849-11ea-9ec5-f56a83c201af.JPG)
* `$ /path/to/php-binary artisan migrate`
* `$ /path/to/php-binary artisan db:seed`
* `$ /path/to/php-binary artisan serve`
<br>

The project now is successfully installed and ready to use at [http://127.0.0.1:8000](http://127.0.0.1:8000).

# License
The software is licensed under the [GNU General Public License v3.0](https://github.com/Damian96/peer-assesment-app/blob/master/LICENSE)
