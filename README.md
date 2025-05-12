# SEO Analysis API

An API for PHP that analyzes web text for SEO elements like headline structure, readability ratings, keyword density, and meta descriptions.

## Features
- Analysis of keyword density is one of the features.
- Evaluation of the meta description
- Headline structure analysis (H1-H3)
- Readability scoring
- OpenAPI/Swagger docs
- Utilizing PHPUnit for unit testing

## Requirements
- PHP 8.0+
- Composer
- MySQL

## Setting up

### 1. Clone Repository
git clone https://github.com/vamsi-1234/seo-api.git

cd seo-api

### 2. Install Requirements from composer.json
composer install

### 3. Configuring the environment
cp env.example .env

Change the.env file using your database credentials:

DB_HOST=localhost

DB_NAME=seo_analysis

DB_USER=your_db_user

DB_PASS=your_db_password

APP_ENV=development

### 4. Database Setup
mysql -u root -p -e "CREATE DATABASE seo_analysis"

mysql -u root -p seo_analysis < database.sql

### 5. Excecute tests
./vendor/bin/phpunit tests

### 6. Launch the server
php -S localhost:8080 -t public

# API Usage

Endpoint

POST /analyze

# Sample Request
```
curl -X POST http://localhost:8080/analyze \
  -H "Content-Type: application/json" \
  -d 
  '{"content":"<html><head><meta name=\"description\" 
  content=\"Sample description\"></head><body><h1>Title</h1><p>Content with keywords</p></body></html>"}'
  ```
  
**Documentation**

Go to the Swagger UI at:

http://localhost:8080/docs

**Demo Page**

public/demo.html contains a simple testing interface. Access it at:

http://localhost:8080/demo.html

