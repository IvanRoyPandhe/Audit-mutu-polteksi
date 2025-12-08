#!/bin/sh

set -e

echo "Starting application..."

# Wait for database to be ready
echo "Waiting for database..."
max_attempts=30
attempt=0
until php artisan migrate --pretend 2>/dev/null || [ $attempt -eq $max_attempts ]; do
    attempt=$((attempt + 1))
    echo "Database is unavailable - attempt $attempt/$max_attempts"
    sleep 2
done

if [ $attempt -eq $max_attempts ]; then
    echo "Failed to connect to database after $max_attempts attempts"
    exit 1
fi

echo "Database is ready!"

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application ready!"

# Execute the main command
exec "$@"
