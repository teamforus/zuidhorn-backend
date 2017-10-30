# Install

```
sudo apt-get install apache2 php libapache2-mod-php7.0 php7.0-xml php7.0-mbstring php7.0-gd php7.0-mysql php7.0-sqlite3 php7.0-mcrypt composer
sudo a2enmod rewrite
```

There are more php modules, but not sure if really required:
```
apt-get -y install libapache2-mod-php7.0;apt-get -y install php7.0; apt-get -y install php7.0-gd;apt-get -y install php7.0-xml;apt-get -y install php7.0-xsl;apt-get -y install php7.0-zip;apt-get -y install php7.0-gmp;apt-get -y install php7.0-cli;apt-get -y install php7.0-imap;apt-get -y install php7.0-json;apt-get -y install php7.0-curl;apt-get -y install php7.0-intl;apt-get -y install php7.0-pgsql;apt-get -y install php7.0-mysql;apt-get -y install php7.0-xmlrpc;apt-get -y install php7.0-common;apt-get -y install php7.0-mcrypt;apt-get -y install php7.0-opcache;apt-get -y install php7.0-sqlite3;apt-get -y install php7.0-mbstring;apt-get -y install php7.0-readline; apt-get -y install php-mbstring; apt-get -y install php-gettext;
```

Setup php environment
```
cd php
composer install
```

Copy and modify the configuration
```
cp .env.example .env
```

## Setup the database
```
sudo apt-get install mysql-server
```

Add the new user
```
mysql --user=root -p mysql

CREATE USER 'forus-backend'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON *.* TO 'forus-backend'@'localhost' WITH GRANT OPTION;

CREATE DATABASE forusbackend;
```

Use migrate:refresh instead to do a full factory reset of the database
```
php artisan migrate --seed
```

Generate app keys
```
php artisan key:generate
php artisan passport:install
```

Run (Testing)
```
php artisan serve --port=9000 --host=0.0.0.0
```

Load front-end
```
git submodule init
git submodule update
```


## Task Scheduling
To perform some automatic tasks, like cleaning old files you should add following command to `/etc/crontab`.
```
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```
