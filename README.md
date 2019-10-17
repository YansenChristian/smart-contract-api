# Api For Seller Apps


## Official Documentation

How to run
composer update in your terminal.

Installation on Windows

If you haven't used Composer before, get the latest version of Composer from getcomposer.org (direct link to .exe here) and install it. 

curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer (This will download and install Composer as a system-wide command named composer, under /usr/local/bin).
You can now use Composer from everywhere on your system (via cmd.exe, PHPStorm or any other tool).

copy .env.temp .env (ask Developer / DevOps for newest env.temp).

go to config directory, copy constants.tmp.php constants.php (ask Developer / DevOps for newest constants.tmp.php).

Local Development Server

If you have PHP installed locally and you would like to use PHP's built-in development server to serve your application, you may use the serve Artisan command. This command will start a development server at http://localhost:
php artisan serve --port=80
note: if you are php artisan serve --port='not using port 80' still have problem when connect to api_notification

You can use web server apache / nginx. If you have apache / nginx installed locally
note: additional step for Mac / linux, Run: sudo chmod -R 777 storage/

