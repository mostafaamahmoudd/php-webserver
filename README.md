# PHP Web Server

A lightweight, pure PHP web server implementation for educational purposes and local development.

## Overview

This project is a from-scratch implementation of a web server written entirely in PHP, demonstrating the fundamentals of HTTP protocol handling, and request/response cycles without relying on pre-built web servers like Apache or Nginx.

This implementation was inspired by and follows concepts from the blog post [Writing a Webserver in Pure PHP](https://station.clancats.com/writing-a-webserver-in-pure-php/) by ClanCats.

## Requirements

- PHP 7.4 or higher
- Sockets extension enabled
- Composer (for autoloading)

## Installation

1. Clone the repository:
```bash
https://github.com/mostafaamahmoudd/php-webserver.git
cd php-webserver
```

2. Install dependencies:
```bash
composer install
```

3. Ensure the sockets extension is enabled in your PHP configuration.

## Usage

### Starting the Server

Run the server from the command line:

```bash
php server
```

This will start the server on the default port 80. To specify a different port:

```bash
php server 8080
```

### Accessing the Server

Once running, open your web browser and navigate to:
```
http://localhost:8080
```

### Custom Request Handling

The server allows you to define custom request handlers:

```php
$server->listen(function (Request $request) {
    // Custom logic based on the request
    if ($request->getUri() === '/hello') {
        return new Response('<h1>Hello World!</h1>');
    }
    
    // Default response
    return new Response('<h1>Welcome to the PHP Web Server</h1>');
});
```

## Project Structure

```
src/
├── Request.php          # HTTP request parser and container
├── Response.php         # HTTP response generator
├── Server.php           # Main server implementation
└── Exception.php        # Custom exception class
server                   # CLI entry point
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Disclaimer

This web server is intended for educational purposes. It is not for production use.
