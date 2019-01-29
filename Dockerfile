FROM andrewmy/phalcon-mongo-base as app

WORKDIR /var/www/html

COPY . /var/www/html

ENV PATH="~/.composer/vendor/bin:./vendor/bin:${PATH}"

CMD bash -c "php-fpm"


FROM app as worker

RUN apk update \
	&& apk add supervisor

COPY ./docker/supervisor.d/app.ini /etc/supervisor.d/app.ini

CMD bash -c "composer install --no-dev && supervisord -c /etc/supervisord.conf"


FROM worker as worker_dev

CMD bash -c "composer install && supervisord -c /etc/supervisord.conf"
