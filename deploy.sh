#!/bin/bash

echo -e "Forus-backend will be reployed now...\n"

echo "Pull the lastest updates..."
git pull

echo "Install the latest packages"
cd php && composer install

echo -e "Adjusting file permissions..."
sudo chmod -R 0774 php/bootstrap/cache php/storage php/public/uploads

echo -e "Restarting supervisor job"
sudo supervisorctl restart forus-backend-worker:*

echo -e "Done"
