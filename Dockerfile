# Utilise l'image officielle PHP avec Apache
FROM php:8.2-apache

# Active les extensions nécessaires (ex: mysqli pour MySQL)
RUN docker-php-ext-install mysqli

# Copie tous les fichiers du projet dans le répertoire web d'Apache
COPY . /var/www/html/

# Droits d'écriture pour les dossiers où on va uploader
RUN chmod -R 777 /var/www/html/documents

# Donne aussi les bons droits pour les logs, si besoin
RUN chmod -R 777 /var/www/html/includes

RUN docker-php-ext-install pdo pdo_mysql