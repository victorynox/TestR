FROM victorynox/lamp

RUN rm -rf /var/www/app/*

ADD /config /var/www/app/config
ADD /data /var/www/app/data
ADD /public /var/www/app/public
ADD /src /var/www/app/src
ADD /templates /var/www/app/templates
ADD /test /var/www/app/test
ADD /vendor /var/www/app/vendor
ADD /.gitignore /var/www/app/.gitignore
ADD /.travis.yml /var/www/app/.travis.yml
ADD /composer.json /var/www/app/composer.json
ADD /composer.lock /var/www/app/composer.lock
ADD /phpcs.xml /var/www/app/phpcs.xml
ADD /phpunit.xml.dist /var/www/app/phpunit.xml.dist
ADD /README.md /var/www/app/README.md

RUN cd /var/www/app && \
composer.phar update