## About Laraplate9 Tailwind AlpineJS

Laraplate9 is a boilerplate based on Laravel 9.x with Tailwind and AlpineJS to make it easier to develop features.


## Features

Laraplate9 takes the pain out of development by easing common tasks used in many web projects, such as:
- [Notus JS: Free Tailwind CSS UI Kit and Admin](https://github.com/creativetimofficial/notus-js).
- Multiple role users (Super, Admin, User)
- Manage permissions
- Manage blog posts
- Real time notifications (with `Pusher`)
- REST API
- Swagger API Documentation (`/api/v1/documentation`)
- Laravel Telescope (`/telescope`)


## Instalation Guide

- Clone repo with `git clone https://github.com/ramdani15/laraplate9-tailwind-alpinejs.git`
- Run `composer install`
- Run `npm install`
- Run `npm run dev`
- Copy `env.example` to `.env` and set it with your own credentials
- Run `php artisan migrate:refresh --seed`
- Run `php artisan key:generate`
- Run `php artisan storage:link`
- Run `php artisan serve`


## Instalation Guide (Docker)

- Clone repo with `git clone https://github.com/ramdani15/laraplate9-tailwind-alpinejs.git`
- Copy `env.example` to `.env` and set it with your own credentials
- Copy `/docker/env.example` to `/docker/.env` and set it with your own credentials
- Run `docker-compose up -d --build`
- Run `docker-compose exec app composer install`
- Run `docker-compose exec app npm install`
- Run `docker-compose exec app npm run dev`
- Run `docker-compose exec app php artisan migrate:refresh --seed`
- Run `docker-compose exec app php artisan key:generate`
- Run `docker-compose exec app php artisan storage:link`
- Open `localhost:8080` for app (based on `DOCKER_NGINX_PORT` in `.env`)
- `localhost:3307` for database (based on `DOCKER_DB_PORT` in `.env`)
- Open `localhost:8082` for phpmyadmin (based on `DOCKER_DB_PANEL_PORT` in `.env`)


### Notes

- Default `Super / Admin / User` password : `test123`
- Code style fixer with [Laravel Pint](https://github.com/laravel/pint) RUN : `sh pint.sh`
### Themes

- **[Notus JS](https://github.com/creativetimofficial/notus-js)**
