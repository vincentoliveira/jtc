ls
ls
git status
git log
git checkout 3f25f0c14eca9639332de6de687b08e8595ebeb9
git checkout -b prodStable
git status
git log
git branch -b
git branch -a
ls
php app/console cache:clear
php app/console cache:clear --env=prod
ls
sudo rm -rf app/cache/*
ls -l
sudo -u www-data rm -rf app/cache/*
ls -l
git pull
git branch -a
git checkout origin/googleAnalytic
git status
git checkout googleAnalytic
git status
cat src/Jtc/UserBundle/Security/
cat src/Jtc/UserBundle/Security/User/Provider/FacebookProvider.php 
cat  src/Jtc/DefaultBundle/Resources/views/layout.html.twig
git pull
git pull
sudo -u www-data git pull
cat  src/Jtc/DefaultBundle/Resources/views/layout.html.twig
sudo -u www-data git pull
git status
