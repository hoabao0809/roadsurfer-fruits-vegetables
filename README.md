# Roadsurfer - Fruits and Vegetables API

## Overview

Roadsurfer Food API is a RESTful web service built using Symfony that allows users to import collections of food, including fruits and vegetables, as well as API endpoints to add new food items into the collection. Additionally, the service allows users to retrieve food data with different unit types (grams or kilograms).

The application follows clean coding practices, separating concerns between controllers, services, repositories.

## Features

- Import food items, including vegetables and fruits and persist to the database.
- API endpoint to add new food item to collection
- API endpoint to query all food items, with the option to return items by desired unit (grams or kilograms), with corresponding quantity
- API endpoint to search for food items based on criterias (name, type, quantity, unit).
- Full validation of incoming data and error handling.

## Technologies Used

## Tech

Used open source projects:

- **[PHP]** - PHP >= 8.2
- **[Symfony]** - An awesome PHP framework for building web applications.
- **Doctrine ORM**: Used for database interaction and entity management.
- **MySQL**: (Or any preferred database supported by Doctrine ORM).

## Installation

### Prerequisites

Ensure that you have the following installed on your machine:
- PHP 8.2 or later
- Composer
- A web server (e.g., Apache or Nginx)
- A database (e.g., MySQL or PostgreSQL)

### Setup Instructions

1. Clone the repository:

   ```bash
   git clone https://github.com/hoabao0809/roadsurfer-fruits-vegetables
   cd roadsurfer-fruits-vegetables
   ```
2. Install PHP dependencies using Composer:

   ```bash
   composer install
   ```
3. Create a `.env` file in the project root and configure your database connection:

   ```bash
   cp .env.example .env
   ```
   Update the .env file to match your database configuration:

   ```bash
   DATABASE_URL="mysql://username:password@127.0.0.1:3306/your_database_name"
    ```
4. Create the database schema:

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:schema:create
   ```
   Run database migrations to set up the tables:

   ```bash
   php bin/console doctrine:migrations:migrate
   ```
5. Start the Symfony server:

   ```bash
    symfony server:start
    ```
   Alternatively, use the PHP built-in server:

   ```bash
   php -S 127.0.0.1:8000 -t public/
   ```
6. Access the application at http://localhost:8000.

---

## Usage (API Endpoints)


### Endpoints

#### 1. Import food items from json file
    GET /api/import-food

Import a list of food items, including vegetables and fruits from request.json file and extract into 2 corresponding collections, then persist collections into storage engine 

#### 2. Get all food items
    GET /api/foods
Retrieve a list of all food items. You can also specify the unit type (grams or kilograms) as a query parameter.
    
- **Optional Query Parameters**:
    - `unit` (values: `g` or `kg`)

Example:
    `GET /api/foods?unit=kg`

#### 3. Create Food
    POST /api/foods

Create a new food item.

- **Request Body** (JSON):

```json
{
  "name": "Apple",
  "type": "fruit",
  "quantity": 500,
  "unit": "g"
}
```

#### 4. Search for Foods
    GET /api/foods/search
Search for food items based on query parameters.

- **Optional Query Parameters:**:
    - `name` (string) - Search for food by name
    - `type` (string) - Filter by food type (e.g., fruit or vegetable)
    - `unit` (string) - Filter by unit (e.g., g or kg)
    - `min_quantity` (integer) - Filter by minimum quantity
    - `max_quantity` (integer) - Filter by maximum quantity 

Example:
    `/api/v1/foods/search?name=Apple&type=fruit&unit=g&min_quantity=500`

- Note: You can combine multiple query parameters to narrow down your search results.

### Testing
To run tests, ensure that PHPUnit is installed and run:

```bash
php bin/phpunit
```
