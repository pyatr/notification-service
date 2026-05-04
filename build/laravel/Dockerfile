FROM php:8.4-fpm-alpine AS laravel
ARG UID=1000
ARG GID=1000
WORKDIR /var/www/html

RUN apk add --no-cache \
    # curl
    curl-dev \
    # Postgres
    libpq-dev \
    # Shadow for adding or modifying user and group id
    shadow \
    # Text editor
    nano \
    # Supervisor for starting all processes
    supervisor \
    # Websockets for amqp
    linux-headers \
    # autoconf for pecl
    autoconf \
    # RabbitMQ
    rabbitmq-c-dev \
    # pecl packages installation
    make \
    # pecl packages installation
    g++ \
    # Install RabbitMQ from pecl
    && pecl channel-update pecl.php.net \
    && pecl install amqp \
    # Install PHP docker packages
    && docker-php-ext-install \
    # curl
    curl \
    # SQL
    pdo \
    # Postgres
    pgsql \
    # Postgres
    pdo_pgsql \
    # Websockets
    sockets \
    && docker-php-ext-enable amqp \
    # Create laravel group and user and create home folder so system folders would not be necessary to use
    && groupadd -g $GID laravel \
    && useradd -u $UID -g laravel -s /sbin/nologin -m laravel

ENV SUPERCRONIC_VERSION=v0.2.45
ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/$SUPERCRONIC_VERSION/supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=e894b193bea75a5ee644e700c59e30eedc804cf7 \
    SUPERCRONIC=supercronic-linux-amd64

RUN curl -fsSLO "$SUPERCRONIC_URL" \
    && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
    && chmod +x "$SUPERCRONIC" \
    && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
    && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

# Copy specific application files and folders
# rather than filtering them with .dockerignore which is not good for cases when you have a file (e.g. a supervisor config)
# that doesn't have to be inside the main folder but is still required to be in container
COPY --chown=laravel:laravel ./bootstrap bootstrap/
COPY --chown=laravel:laravel ./public public/
COPY --chown=laravel:laravel ./storage storage/
COPY --chown=laravel:laravel ./resources resources/
COPY --chown=laravel:laravel .env artisan composer.json composer.lock ./
COPY --chown=laravel:laravel ./tests tests/
COPY --chown=laravel:laravel ./config config/
COPY --chown=laravel:laravel ./routes routes/
COPY --chown=laravel:laravel ./database database/
COPY --chown=laravel:laravel ./app app/
COPY --chown=laravel:laravel crontab /etc/crontab/laravel

# Create log and PID folder for supervisor. It does not create any of its folders
RUN mkdir -m 750 -p \
    /home/laravel/run/supervisor \
    /home/laravel/log/supervisor \
    && chown laravel:laravel -R \
    /home/laravel/run/supervisor \
    /home/laravel/log/supervisor

COPY --chown=laravel:laravel --from=composer:2.9.3 /usr/bin/composer /usr/bin/composer


FROM laravel AS development
COPY --chown=laravel:laravel supervisord.dev.ini /home/laravel/etc/supervisor.d/supervisord.ini
COPY --chown=laravel:laravel start-dev.sh start.sh
COPY --chown=laravel:laravel pre-start.sh pre-start.sh
RUN chmod +x pre-start.sh && \
    chmod +x start.sh
USER laravel:laravel
CMD ["sh", "/var/www/html/pre-start.sh"]

FROM laravel AS production
COPY --chown=laravel:laravel supervisord.ini /home/laravel/etc/supervisor.d/supervisord.ini
COPY --chown=laravel:laravel start.sh start.sh
RUN chmod +x start.sh
USER laravel:laravel
RUN composer install --optimize-autoloader --no-interaction --no-dev && \
    rm -rf /usr/bin/composer && \
    php artisan key:generate && \
    php artisan optimize:clear && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan storage:link
CMD ["supervisord", "-c", "/home/laravel/etc/supervisor.d/supervisord.ini"]
