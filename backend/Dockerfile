FROM php:8.1-apache

RUN apt-get update && apt-get install -y unixodbc-dev gnupg2 && \
    curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql17 && \
    pecl install sqlsrv && docker-php-ext-enable sqlsrv
