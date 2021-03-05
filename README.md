# SymfonyShop

I'm building this eshop from scratch to improve my skills.

## Technologies

### Backend

- PHP
- Symfony
- MySQL
- Doctrine
  
### Frontend

- Twig
- Bootstrap
- JavaScript

### Payment

- Stripe API

## Project Setup

If you wish to install this project, clone this repo and run:

`composer install`

Setup your .env.local with your database credentials and run:

`php bin/console doctrine:database:create`

`php bin/console doctrine:migrations:migrate`

Eventually, you can load fixtures if you wish to populate your database:

`php bin/console doctrine:fixtures:load`

To use Stripe, you need to create your own account and get your public and secret key.
