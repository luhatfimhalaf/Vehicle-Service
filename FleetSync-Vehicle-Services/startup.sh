#!/bin/bash
echo "Running vehicle service migrations.."
cd /app/vehicle
php artisan migrate

php artisan serve --host=0.0.0.0 --port=8000