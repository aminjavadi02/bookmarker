#!/bin/sh

# Exit immediately if a command exits with a non-zero status
set -e 

# Wait for the database to be ready
echo "Waiting for the database to be ready..."
while ! nc -z postgres 5432; do
  sleep 1
done
echo "Database is ready!"

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Start the queue worker
echo "Starting Laravel queue worker..."
php artisan queue:work --daemon &

exec "$@"
