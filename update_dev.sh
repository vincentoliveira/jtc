#! /bin/sh

PULL=$1

if [ ! -z $PULL ]
then
    git pull origin develop
    php composer.phar update
fi

chmod -R 0777 app/cache
chmod -R 0777 app/logs

php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force

php app/console doctrine:fixtures:load --append

php app/console assets:install web --symlink

php app/console cache:clear --no-warmup
rm -rf app/cache/*
chmod -R 0777 app/cache
chmod -R 0777 app/logs
chown -R www-data:www-data *
