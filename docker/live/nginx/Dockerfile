FROM nginx:1.15

COPY ./ /usr/share/nginx/app
COPY ./docker/live/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
WORKDIR /usr/share/nginx/app

