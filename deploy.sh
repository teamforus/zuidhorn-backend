#!/bin/bash

echo -e "Forus-backend will be reployed now...\n"

echo "Pull the lastest updates..."
git pull
git submodule update

echo "Install the latest packages"
cd php && composer install && cd ..

echo -e "Adjusting file permissions..."
sudo chown -R forus:www-data ./php
sudo chmod -R 0775 ./php/bootstrap/cache ./php/storage ./php/public/uploads

echo -e "Restarting supervisor job"
sudo supervisorctl restart forus-backend-worker:*

echo -e "Done"
