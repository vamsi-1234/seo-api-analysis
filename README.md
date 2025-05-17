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
- Docker and Docker desktop

## Setting up

### 1. Clone Repository
git clone https://github.com/vamsi-1234/seo-api.git

cd seo-api


### 3. Configuring the environment
cp env.example .env

Change the.env file using your database credentials:

DB_HOST=db

DB_NAME=seo_analysis

DB_USER=root

DB_PASS=secret

APP_ENV=development

### 4. Running via Docker

docker-compose build --no-cache


docker-compose up -d


cat database.sql | docker-compose exec -T db mysql -u root -psecret seo_analysis


### 5. Excecute tests

docker-compose exec app composer install


docker-compose exec app sh -c "cd /var/www/html && ./vendor/bin/phpunit tests"


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

