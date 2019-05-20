FROM        nginx:mainline-alpine
MAINTAINER  Arnaud Ponel <arnaud@xpressive.io>

RUN         apk add --no-cache \
            --repository http://dl-cdn.alpinelinux.org/alpine/edge/main \
            --repository  http://dl-cdn.alpinelinux.org/alpine/edge/community \
            shadow

RUN         usermod -u 1000 nginx && \
            groupmod -g 1000 nginx

COPY        ./conf.d /etc/nginx/conf.d

RUN         mkdir -p /app

VOLUME      /app
WORKDIR     /app
