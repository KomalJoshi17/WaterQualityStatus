# Use the official PHP image
FROM php:8.1-cli

# Set working directory
WORKDIR /app

# Copy all project files to the container
COPY . .

# Expose port 10000 (required by Render)
EXPOSE 10000

# Run PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000"]
