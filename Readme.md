# Swoole, Mezzio and Symfony DI skeleton
This repo is a skeleton of a PHP project which uses Swoole, Mezzio, PSR-15 Request Handlers and Middleware

## Dev set up
```bash
cp .env.dist .env
make composer install
docker-compose up -d
```

## Build production image
```bash
docker build -t swoole-mezzio-symfony-di .
docker run -e ENV=prod -p 30000:30000 swoole-mezzio-symfony-di
```
