ARG BASE_IMAGE=ratulsaqibkhan/php8-swoole
ARG IMAGE_VERSION=3.0.0
FROM ${BASE_IMAGE}:${IMAGE_VERSION}

LABEL maintainer="Md Nazmus Saqib Khan<ratulkhan.jhenidah@gmail.com>"

# App Root Path relative to context
ARG HOST_APP_ROOT_DIR="./backend/"
ARG WORK_DIR_PATH="/var/www/html"

ARG BUILD_WATCH_MODE
ARG EXTRA_COMMAND="--watch"
ARG BUILD_MODE="prod"
ARG TIMEZONE="Asia/Dhaka"

# Timezone
ENV TZ="${TIMEZONE}"

USER root

RUN echo "-- Configure Timezone --" \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone
RUN echo "--- Installing Other dependencies ---" \
    && if [ ${BUILD_MODE} = "dev" ]; then \
    echo "--- Installing Nodejs ---" \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -y yarn \
    ;fi 
RUN echo "--- Clean Up ---" \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN echo "-- Symlink creating --" \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

COPY ./docker/config/php.ini /usr/local/etc/php/conf.d/app-php.ini

# PHP INI Settings for production by default
ENV PHP_INI_DATE_TIMEZONE="${TIMEZONE}"

WORKDIR ${WORK_DIR_PATH}

RUN chown -R www-data:www-data /var/www \
    && setcap "cap_net_bind_service=+ep" /usr/local/bin/php

USER www-data

# Composer Packages Installation
COPY --chown=www-data:www-data ${HOST_APP_ROOT_DIR}composer.* ${WORK_DIR_PATH}/
RUN composerInstallArgs="--no-scripts --no-interaction --no-autoloader" \
    && export composerInstallArgs \
    && if [ ${BUILD_MODE} = "prod" ]; then \
    composerInstallArgs="$composerInstallArgs --no-dev" \
    && export composerInstallArgs \
    ;fi \
    && composer install $composerInstallArgs

# App ENV Settings
ENV APP_NAME="COVID_VACCINATE" \
    APP_ENV="production" \
    APP_DEBUG=false \
    APP_URL="http://localhost/" \
    APP_TIMEZONE="${TIMEZONE}" \
    ARTISAN_COMMAND="" \
    WORKER_COUNT="4" \
    TASK_WORKER_COUNT="6" \
    LOG_PREFIX="COVID_VACCINATE_SERVICE_LOGGER" \
    LOG_CHANNEL="stderr" \
    DB_TIMEZONE="+06:00" \
    DB_USE_DEFAULT_STRING_LENGTH=false \
    OCTANE_SERVER="swoole"

# Composer DumpAutoload
COPY --chown=www-data:www-data ${HOST_APP_ROOT_DIR} ${WORK_DIR_PATH}
RUN composerDumpAutoloadArgs="--optimize" \
    && export composerDumpAutoloadArgs \
    && if [ ${BUILD_MODE} = "prod" ]; then \
    composerDumpAutoloadArgs="$composerDumpAutoloadArgs --classmap-authoritative" \
    && export composerDumpAutoloadArgs \
    ;fi \
    && composer dump-autoload $composerDumpAutoloadArgs

RUN chmod -R ug+w bootstrap/ storage/

# NPM Installation for local development
COPY ./backend/package.json ./

# RUN yarn install
RUN if [ ${BUILD_MODE} = "dev" ]; then \
    yarn install \
    ;fi

ENV ARTISAN_COMMAND=${BUILD_WATCH_MODE:+${EXTRA_COMMAND}}

# ---------- #
# WEB - NGINX
# ---------- #

# Web
ENV NGINX_VHOST_ENABLE_HTTP_TRAFFIC="true" \
    NGINX_VHOST_ENABLE_HTTPS_TRAFFIC="true" \
    NGINX_VHOST_SERVER_NAME="_" \
    NGINX_VHOST_HTTP_SERVER_NAME="_" \
    NGINX_VHOST_CLIENT_MAX_BODY_SIZE="2m"

COPY --chown=root:root ./docker/web/includes/*.conf /etc/nginx/includes/
COPY --chown=root:root ./docker/web/nginx.conf /etc/nginx/nginx.conf

COPY --chown=root:root ./docker/web/conf.d/vhost-http.conf /etc/nginx/conf.d/default.conf

COPY --chown=www-data:www-data ./docker/bin/docker-entrypoint.sh /usr/local/bin/entrypoint.sh

USER root

WORKDIR ${WORK_DIR_PATH}

# --------- #
# SUPERVISOR
# --------- #
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/
COPY ./docker/supervisor/conf.d/* /etc/supervisor/conf.d/

RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
