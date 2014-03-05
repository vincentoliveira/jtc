#! /bin/sh

PULL=$1

if [ ! -z $PULL ]
then
    git pull origin develop
    php composer.phar update
fi

chmod -R 0777 app/cache
chmod -R 0777 app/logs

sudo -u www-data php app/console doctrine:database:drop --force --env=prod
sudo -u www-data php app/console doctrine:database:create --env=prod
sudo -u www-data php app/console doctrine:schema:update --force --env=prod

sudo -u www-data php app/console doctrine:fixtures:load --append --env=prod

sudo -u www-data php app/console assets:install web --symlink

sudo -u www-data php app/console cache:clear --no-warmup --env=prod
chmod -R 0777 app/cache
chmod -R 0777 app/logs
chown -R www-data:www-data *
