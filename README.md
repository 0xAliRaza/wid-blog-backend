<h1 align="center"> Write It Down (Backend) </h1>
<p align="center">
	<a href="#using-docker-recommended">Quick Install (docker)</a> |
	<a href="#about">About</a> |
	<a href="#features">Features</a> |
	<a href="#authors">Authors</a>
</p>


<p align="center">
	<a href="https://cloud.docker.com/repository/docker/0xaliraza/wid-blog-backend/builds"><img alt="Docker Build Status" src="https://img.shields.io/docker/cloud/build/0xaliraza/wid-blog-backend" />
	</a><a href="https://cloud.docker.com/repository/docker/0xaliraza/wid-blog-backend/"><img alt="Docker Build Automation Status" src="https://img.shields.io/docker/cloud/automated/0xaliraza/wid-blog-backend" />
	</a>
</p>

Also check out the [wid-blog-frontend](http://github.com/0xaliraza/wid-blog-frontend).

Write It Down - Backend is a free to use headless blogging CMS built with [Laravel 7.x](https://laravel.com/). It uses [JWT](https://jwt.io) for authentication and can be consumed by any frontend.
This project was coded for the sole purpose of supporting [wid-blog-frontend](http://www.github.com/0xaliraza/wid-blog-frontend).

![wid-blog-backend preview](https://i.imgur.com/B15n7sm.png)

## About

Write It Down - Backend is a headless blogging CMS which can be used by any kind of frontend be it a SPA, PWA and just simple jQuery project that utilizes AJAX. The purpose of its existence is to act as an API for [WID-Blog-Frontend](http://github.com/0xaliraza/wid-blog-frontend).

This project was inspired by [Ghost](http://ghost.org) and [Medium](http://medium.com) but it only includes the minimal and most necessary features.

## Features

-   Uses [tymon\jwt-auth](https://jwt-auth.readthedocs.io/en/docs/) for user Authentication utilizing [JSON Web Tokens](https://jwt.io) .

-   Role based authorization based on these three roles:

	-   `superadmin`: Can do all actions such as creating a post (article), a page, adding tags, adding new users or editing existing users and changing blog settings.

	-   `admin`: Can do all the things a superadmin can do but can't create or modify existing superadmins and their posts.

	-   `editor`: Can only show their content, edit their own profile but can't modify or show or modify content of superadmins or admins.

-   All the basic CRUD features of a headless blog which includes posts, pages, tags, meta data, users, and images.

## Getting Started

These instructions will get you a copy of this project running on your local machine in dev mode.

### Using Docker (recommended)

This is the easiest and recommended way because you shall only be required to install the below prerequisites and nothing else.

**Prerequisites:**

-   [Docker](https://docs.docker.com/engine/install/ubuntu/)

-   [Docker Compose](https://docs.docker.com/compose/install/)

-   [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)

-   A command line interface

**Cloning repository:**

Start by cloning the repository on your local machine using git:

```
git clone https://github.com/0xaliraza/wid-blog-backend
```

Change directory to the project folder:

```
cd wid-blog-backend
```

**Setting up Laravel:**

Create `.env` file by running:

```
cp .env.example .env
```

Now edit `.env` file and change `DB_` related environment variables by replacing them with following:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=wid_blog_backend
DB_USERNAME=<ANY_CUSTOM_USERNAME_HERE>
DB_PASSWORD=<ANY_CUSTOM_PASSWORD_HERE>
```

Install Composer dependencies:

```
docker-compose exec app composer install
```

Generate Laravel application key:

```
docker-compose exec app php artisan key:generate
```

Running the laravel migrations:

```
docker-compose exec app php artisan migrate
```

**Setting up Docker:**

Pull all the required docker images:

```
docker-compose pull
```

Run Docker Compose:

```
docker-compose up --d
```

By now, you should have a running Laravel instance at http://localhost:8000 without any errors.

Also, to your surprise, you'll also get a [phpmyadmin](https://www.phpmyadmin.net) instance running at http://localhost:8080 😉 so you can inspect the database and stuff, how cool is that?

Run `docker ps` to check running docker containers.

### Using Laravel Artisan

**Prerequisites:**

-   Basic knowledge of laravel project ofcourse.

You can use [this](https://gist.github.com/hootlex/da59b91c628a6688ceb1) easy guide to setup this project locally on your machine using [Artisan](https://laravel.com/docs/8.x/artisan).

## Authors

[Ali Raza](https://0xali.com) (me) **🙃**

## Find Me Online

Feel free to contact me for any kind of help or information. Let's get connected! :)

-   [Website](https://0xali.com)

-   [Github](https://github.com/0xaliraza)

-   [Twitter](https://twitter.com/0xaliraza)

-   [Medium](https://0xali.medium.com)

-   [Linkedin](https://www.linkedin.com/in/ali-raza-061130202/)
