chmod -R 0777 app/cache
chmod -R 0777 app/logs

php app/console assets:install web --symlink
# php app/console fos:js-routing:dump

rm -rf app/cache/*
chmod -R 0777 app/cache
chmod -R 0777 app/logs
chown -R www-data:www-data *
