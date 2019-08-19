#!/bin/sh
echo pwd
echo "Downloading phpMyAdmin"
curl -O https://files.phpmyadmin.net/phpMyAdmin/4.9.0.1/phpMyAdmin-4.9.0.1-all-languages.zip
echo "Unzipping phpMyAdmin"
unzip phpMyAdmin-4.9.0.1-all-languages.zip
echo "Renaming phpMyAdmin"
mv phpMyAdmin-4.9.0.1-all-languages phpMyAdmin