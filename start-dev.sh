composer install --optimize-autoloader --no-interaction
php artisan key:generate
php artisan optimize:clear
php artisan migrate
php artisan storage:link
