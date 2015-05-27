apt-get update -y -f --force-yes
apt-get upgrade -y -f --force-yes
apt-get install -y -f --force-yes vim wget curl

debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
apt-get -y install mysql-server
apt-get -y install apache2-mpm-prefork php5-cli php5 php5-common php5-curl php5-intl php5-mcrypt php5-mysql php-apc php5-json php5-xdebug

a2enmod php5 rewrite

sed -i 's/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=vagrant/g' /etc/apache2/envvars
sed -i 's/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=vagrant/g' /etc/apache2/envvars

sed -i 's#;date.timezone =#date.timezone = Europe/Paris#g' /etc/php5/cli/php.ini
sed -i 's#DocumentRoot /var/www$#DocumentRoot /var/www/web#g' /etc/apache2/sites-available/default
sed -i 's#<Directory /var/www>#<Directory /var/www/web>#g' /etc/apache2/sites-available/default
sed -i 's#Options Indexes FollowSymLinks MultiViews#Options -Indexes FollowSymLinks MultiViews#g' /etc/apache2/sites-available/default
sed -i 's#AllowOverride None#AllowOverride All#g' /etc/apache2/sites-available/default
sed -i 's#bind-address#;bind-address#g' /etc/mysql/my.cnf

echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root'; FLUSH PRIVILEGES;" | mysql -u root -proot

echo "CREATE DATABASE `zecolis_db` CHARACTER SET utf8 COLLATE utf8_general_ci;" | mysql -u root -proot

/etc/init.d/apache2 restart
/etc/init.d/mysql restart

