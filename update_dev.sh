#! /bin/sh

PULL=$1

if [ ! -z $PULL ]
then
    git pull origin develop
    php composer.phar update
fi

chmod -R 0777 app/cache
chmod -R 0777 app/logs

sudo -u www-data php app/console doctrine:database:drop --force
sudo -u www-data php app/console doctrine:database:create
sudo -u www-data php app/console doctrine:schema:update --force

sudo -u www-data php app/console assets:install web --symlink

# Add users
sudo -u www-data php app/console fos:user:create test test@zecolis.com test
sudo -u www-data php app/console fos:user:create admin admin@zecolis.com admin --super-admin

sudo -u www-data php app/console cache:clear --no-warmup
chmod -R 0777 app/cache
chmod -R 0777 app/logs
chown -R www-data:www-data *
