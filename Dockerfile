FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    python3-full \
    python3-pip \
    python3-dev \
    libgl1-mesa-glx \
    libglib2.0-0 \
    libsm6 \
    libxext6 \
    libxrender-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql gd zip

# Create virtual environment
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Install Python packages
COPY requirements.txt /tmp/
RUN . /opt/venv/bin/activate && \
    pip3 install --no-cache-dir --upgrade pip && \
    pip3 install --no-cache-dir -r /tmp/requirements.txt

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Create necessary directories and set permissions
RUN mkdir -p /var/www/storage/logs && \
    mkdir -p /var/www/storage/framework/{sessions,views,cache} && \
    mkdir -p /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www

# Copy application files
COPY . .

# Set execute permission for Python script
RUN chmod +x scripts/analyze_image_with_color.py

# Install composer dependencies
RUN composer install --no-scripts --no-autoloader --no-dev

# Generate autoload files
RUN composer dump-autoload --optimize

# Final permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www/storage && \
    chmod -R 755 /var/www/bootstrap/cache

# Configure PHP-FPM
RUN echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 35" >> /usr/local/etc/php-fpm.d/www.conf

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
