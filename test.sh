#! /bin/sh

sudo -u www-data php app/console doctrine:database:drop --force --env=test
sudo -u www-data php app/console doctrine:database:create --env=test
sudo -u www-data php app/console doctrine:schema:update --force --env=test

# Add users
sudo -u www-data php app/console fos:user:create test test@sharimg.com test123 --env=test
sudo -u www-data php app/console fos:user:create admin admin@sharimg.com admin123 --super-admin --env=test

phpunit -c app/ --debug
