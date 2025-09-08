# Use an official PHP image with Apache as the base image
FROM php:8.2-apache

# Install system dependencies, including Python, pip, and build tools for dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    python3 \
    python3-pip \
    git \
    libsqlite3-dev \
    curl \
    rustc \
    && rm -rf /var/lib/apt/lists/*

# Copy the application source code to the web server's root directory
COPY . /var/www/html/

# Copy the virtual host configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the Apache rewrite module for clean URLs
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html/

# Install Python dependencies
RUN pip3 install --no-cache-dir -r requirements.txt

# Ensure the apache user can write to the user_data directory
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80 for the web server
EXPOSE 80

# The main command to start the Apache server (this is often the default, but explicit is better)
CMD ["apache2-foreground"]
