#! /bin/sh

sudo -u www-data php app/console doctrine:database:drop --force --env=test
sudo -u www-data php app/console doctrine:database:create --env=test
sudo -u www-data php app/console doctrine:schema:update --force --env=test

#phpunit -c app/ --debug
bin/behat @JtcDefaultBundle
