# SEO Analysis API

A PHP API for analyzing web content for SEO factors including keyword density, readability scores, headline structure, and meta descriptions.

## Features
- Keyword density analysis
- Readability scoring
- Headline structure analysis (H1-H3)
- Meta description evaluation
- Swagger/OpenAPI documentation
- Unit testing with PHPUnit

## Prerequisites
- PHP 8.0+
- Composer
- MySQL

## Installation

### 1. Clone Repository
git clone https://github.com/vamsi-1234/seo-api.git
cd seo-api

### 2. Install Dependencies
composer install

### 3. Environment Configuration
cp env.example .env
Edit .env with your database credentials:
DB_HOST=localhost
DB_NAME=seo_analysis
DB_USER=your_db_user
DB_PASS=your_db_password
APP_ENV=development

### 4. Database Setup
mysql -u root -p -e "CREATE DATABASE seo_analysis"

mysql -u root -p seo_analysis < database.sql

### 5. Run tests
./vendor/bin/phpunit tests

### 6. Running the server
php -S localhost:8080 -t public

# API Usage

Endpoint

POST /analyze

# Sample Request

curl -X POST http://localhost:8080/analyze \
  -H "Content-Type: application/json" \
  -d 
  '{"content":"<html><head><meta name=\"description\" 
  content=\"Sample description\"></head><body><h1>Title</h1><p>Content with keywords</p></body></html>"}'
  
**Documentation**

Access Swagger UI at:

http://localhost:8080/docs

**Demo Page**

public/demo.html contains a simple testing interface. Access it at:

http://localhost:8080/demo.html

