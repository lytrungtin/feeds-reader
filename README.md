
## Requirements

* The application must be developed by using a php framework and follow coding standard of that framework.
* As a developer, I want to run a command which help me to setup database easily with one run.
* As a developer, I want to run a command which accepts the feed urls (separated by comma) as argument to grab items from given urls. Duplicate items are accepted.
* As a developer, I want to see output of the command not only in shell but also in pre-defined log file. The log file should be defined as a parameter of the application.
* As a user, I want to see the list of items which were grabbed by running the command line. I also should see the pagination if there are more than one page. The page size is up to you.
* As a user, I want to filter items by category name on list of items.
* As a user, I want to create new item manually
* As a user, I want to update/delete an item

## Summary

* The application have been developed by using Laravel framework.
 
## How to Validate/Test Locally

- Download composer https://getcomposer.org/download/
- Run this command where you like to place the project folder: `git clone https://git.nfq.asia/php.lytrungtin/base-php`
- Rename `.env.example` file to `.env`inside your project root and fill the database information.
  (windows wont let you do it, so you have to open your console cd your project root directory and run `mv .env.example .env` )
- Open the console and cd your project root directory
- Run `composer install` or ```php composer.phar install```
1. Go to `.env` to set up a database connection.
1. Define log file name for command scraping feeds in `.env` also. Example: `LOG_COMMAND_FILE=command.log`
1. Run `php artisan key:generate`
1. Create a database locally named homestead utf8_general_ci
1. Run in root folder this command `php artisan migrate` to migrate a database. 
1. Run in root folder this command `vendor/bin/phpunit` to run unit test.
1. Run in root folder this command `php artisan feed:scrap {urls}` to begin scrap items from feeds. With {urls} is list of feeds url. 
Example:
```
php artisan feed:scrap https://www.feedforall.com/sample.xml,https://www.feedforall.com/sample-feed.xml,https://www.feedforall.com/blog-feed.xml,http://www.rss-specifications.com/blog-feed.xml 
```

1. Run in root folder this command `php artisan serve` so you can now access your project at localhost:8000
1. Go to http://localhost:8000/register to register new account.
1. Go to http://localhost:8000/login to login account.
1. Go to http://localhost:8000/items/create to create manually new item.
1. Go to http://localhost:8000/items to view list of items and use filter items by category.
1. Click to button edit on http://localhost:8000/items to go to edit page of the item.
1. Click to button delete on http://localhost:8000/items to delete that item.
1. Run `php artisan listing:grabbed` or `php artisan listing:grabbed --page=1` to see the list of items which were grabbed by running the command line.
