# Install

```
sudo apt-get install apache2 php libapache2-mod-php7.0 php7.0-xml php7.0-mbstring php7.0-gd php7.0-mysql php7.0-sqlite3 php7.0-mcrypt composer
sudo a2enmod rewrite
```

```
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
