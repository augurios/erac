#!/bin/bash

red=`tput setaf 1`
green=`tput setaf 2`
blue=`tput setaf 4`
reset=`tput sgr0`

echo -n 'Install for production environment? (Y/n):'

read -n 1 $ans

if [[ ( "$ans" == "Y" ) || ( "$ans" == "y" ) ]]
then
  local=''
else
  local='local'
fi

if [[ $local == "local" ]]
then
  run --rm -w /var/www php composer install
else
  
  run --rm -w /var/www php composer install --optimize-autoloader --no-dev
fi

run npm install

run --rm -w /var/www php php artisan key:generate

run --rm -w /var/www php chmod -R 777 /var/www/storage

run --rm -w /var/www php php artisan migrate:fresh --seed

echo ""
echo "${green}Installation complete.${reset}"
echo ""

# Exit from the script with success (0)
exit 0