## Ensure we have following installed and running
    > XAMPP (7.4.20 / PHP 7.4.20 - 64bit preferred)
    > git

## add a new database in mysql
    > by going to localhost/phpmyadmin
    > add database by the name of old_lms


## Install Composer
    > install composer from (https://getcomposer.org/download/)[Composer website]

## install laravel on your PC
    > composer global require laravel/installer
    


## in your xampp/htdocs root, Open the terminal/CMD, run the following commands
    > git clone https://gitlab.com/ahmednawazbutt/learning-management-system-all.git

## now move to your project by following these commands
    > cd learning-management-system-all/website (moves to working directory of project)
    > composer update (updates dependenciesd)
    > php artisan key:generate (generate new application session key)
    > php artisan migrate:fresh (installs db tables)
    > php artisan passport:install --force (intalls and setsup API functionality)
    > php artisan db:seed (adds base data in db tables)
    > composer dump-autoload (updates references to classes and files)


## now in your browser, visit the following link
    > (http://localhost/learning-management-system-all/website/public/auth/login)[Login Page Link]
    > here are the a teacher's credentails
        >> Email: teacher@lms.com
        >> Password: teacher123

# References:
    > (https://www.apachefriends.org/download.html)[XAMPP - 7.4.20 / PHP 7.4.20	]
    > (https://git-scm.com/downloads)[Git]
    > (https://getcomposer.org)[Composer]
    > (https://laravel.com/docs/8.x)[Laravel website]