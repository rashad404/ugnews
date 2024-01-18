# PHP resmi imajını kullan
FROM php:7.1-apache

# Çalışma dizinini ayarla
WORKDIR /var/www/html

# Proje dosyalarını kopyala
COPY . /var/www/html

# Apache mod_rewrite'ı etkinleştir
RUN a2enmod rewrite

# Gerekirse, PHP bağımlılıklarını yükle (örneğin, Composer kullanılıyorsa)
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer install

# Yapılandırmaları kopyala (örneğin, bir VirtualHost dosyası)
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Container'ın portunu dış dünyaya aç
EXPOSE 80

# Container başladığında çalışacak komut
CMD ["apache2-foreground"]
