#!/bin/sh
#
# Install Booking Web Application
#
#
echo "===========  Connecting to GitHub =============";

## git pull

echo "===========  Starting Migration =============";

php artisan migrate:refresh --seed

echo "===========  Starting Cache Clear =============";

php artisan cache:clear
php artisan config:clear

echo "===========  Route Clear =============";

php artisan route:clear

echo "===========  View Clear =============";

php artisan view:clear

echo "===========  Caching View=============";

php artisan view:cache
php artisan config:cache

echo "===========  Done =============";

