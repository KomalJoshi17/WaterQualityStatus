# Use official PHP image
FROM php:8.1-cli

# Install dependencies (if needed)
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Copy your project files into the container
COPY . /app
WORKDIR /app

# Expose port
EXPOSE 10000

# Start PHP server
CMD ["php", "-S", "0.0.0.0:10000"]
