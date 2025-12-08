#!/bin/sh

set -e

echo "Starting application..."

# Debug environment variables
echo "=== Database Configuration ==="
echo "DB_CONNECTION: ${DB_CONNECTION}"
echo "DB_HOST: ${DB_HOST}"
echo "DB_PORT: ${DB_PORT}"
echo "DB_DATABASE: ${DB_DATABASE}"
echo "DB_USERNAME: ${DB_USERNAME}"
echo "=============================="

# Test database connection with psql
echo "Testing database connection..."
if command -v psql > /dev/null; then
    echo "Testing with psql..."
    PGPASSWORD="${DB_PASSWORD}" psql -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USERNAME}" -d "${DB_DATABASE}" -c "SELECT 1;" 2>&1 && echo "✓ Database connection successful" || echo "✗ Database connection failed"
fi

# Try to run migrations (but don't fail if it doesn't work)
echo "Attempting to run migrations..."
if php artisan migrate --force 2>&1; then
    echo "✓ Migrations completed"
else
    echo "✗ Migrations failed - will retry later"
    echo "Container will start anyway for debugging"
fi

# Clear and cache config (ignore errors)
echo "Optimizing application..."
php artisan config:cache 2>&1 || echo "Config cache skipped"
php artisan route:cache 2>&1 || echo "Route cache skipped"
php artisan view:cache 2>&1 || echo "View cache skipped"

echo "Application starting..."

# Execute the main command
exec "$@"
