FROM shinsenter/symfony:php8.1

COPY src/ /var/www/html

# You may add your constructions from here
# For example:
ADD --chown=$APP_USER:$APP_GROUP ./ /var/www/html/

EXPOSE 90