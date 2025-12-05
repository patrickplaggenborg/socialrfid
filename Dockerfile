FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Suppress Apache warning about ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configure Apache DirectoryIndex to include index.htm
RUN echo "<Directory /var/www/html>" >> /etc/apache2/apache2.conf && \
    echo "    DirectoryIndex index.php index.html index.htm" >> /etc/apache2/apache2.conf && \
    echo "    Options Indexes FollowSymLinks" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf

# Copy web-accessible files
COPY public/ /var/www/html/

# Copy private config files (outside web root)
COPY private/ /var/www/private/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html /var/www/private && \
    chmod -R 755 /var/www/html

# Enable Apache modules
RUN a2enmod rewrite

EXPOSE 80

