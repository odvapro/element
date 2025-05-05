FROM odva/phalcon:5.0

RUN apt-get update --fix-missing
RUN apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql