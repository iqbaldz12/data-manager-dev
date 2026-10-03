#!/bin/sh

# 1. Instal PHP dan utilitas yang diperlukan
dnf clean metadata
dnf install -y php8.2 php8.2-{common,mbstring,gd,bcmath,xml,fpm,intl,zip}
dnf install -y wget

# 2. Unduh dan instal Composer
EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
then
    >&2 echo 'ERROR: Invalid installer checksum'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php --quiet
rm composer-setup.php

# 3. Instal dependensi PHP
php composer.phar install

# 4. Build frontend (Tailwind/Vite)
npm install
npm run build
