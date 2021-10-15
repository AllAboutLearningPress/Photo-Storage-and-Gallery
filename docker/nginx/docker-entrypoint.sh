#!/usr/bin/env sh
set -eu

envsubst '${APP_URL}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

exec "$@"
