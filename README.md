# PHP_Laravel12_Blog_System

A simple and clean blog system built with Laravel 12. This project allows users to create, read, update, and delete blog posts with image upload functionality.

---

## Features

* Full CRUD operations for blog posts
* Image upload support
* Responsive design using Bootstrap
* Form validation
* Pagination
* Search functionality
* File storage management
* Clean and modern UI

---

## Prerequisites

Before you begin, ensure you have the following installed:

* PHP 8.1 or higher
* Composer
* MySQL 5.7 or higher
* Node.js and NPM (optional, for frontend assets)
* Git

---

## Installation

Follow these steps to set up the project on your local machine.

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-blog-system.git
cd laravel-blog-system
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

Update database configuration in the `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_blog
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Create Database

Create a MySQL database named `laravel_blog` or use the database name configured in `.env`.

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Create Storage Link

```bash
php artisan storage:link
```

### Step 8: Seed Database (Optional)

```bash
php artisan db:seed
```

### Step 9: Start Development Server

```bash
php artisan serve
```

Application URL:

```
http://localhost:8000
```
---
## Screenshot
<img width="1737" height="972" alt="image" src="https://github.com/user-attachments/assets/0af890d5-777d-491b-9af9-faf40b892da0" />
<img width="1760" height="841" alt="image" src="https://github.com/user-attachments/assets/3b59723c-6385-4944-905d-9a6afa35f4f6" />
<img width="1677" height="973" alt="image" src="https://github.com/user-attachments/assets/22f80df6-7db9-4de1-9723-13d26b63afac" />


---

## Project Structure

```
laravel-blog-system/
├── app/
│   ├── Http/Controllers/PostController.php
│   └── Models/Post.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── storage/
├── resources/
│   └── views/
│       ├── layouts/
│       └── posts/
├── routes/
│   └── web.php
├── storage/
└── tests/
```

---

## Database Schema

### Posts Table

* id (Primary key)
* title (string, required)
* content (text, required)
* image (string, nullable)
* created_at (timestamp)
* updated_at (timestamp)

---

## Routes

| Method    | URL                | Description             |
| --------- | ------------------ | ----------------------- |
| GET       | /                  | Redirect to posts index |
| GET       | /posts             | List all posts          |
| GET       | /posts/create      | Create post form        |
| POST      | /posts             | Store post              |
| GET       | /posts/{post}      | View post               |
| GET       | /posts/{post}/edit | Edit post form          |
| PUT/PATCH | /posts/{post}      | Update post             |
| DELETE    | /posts/{post}      | Delete post             |

---

## Usage Guide

### Creating a Post

* Click on Create Post
* Enter title and content
* Upload image (optional)
* Submit the form

### Viewing Posts

* Posts are displayed with pagination
* Each post shows title, image, excerpt, and actions

### Editing a Post

* Click Edit on a post
* Update details
* Save changes

### Deleting a Post

* Click Delete
* Confirm deletion

### Searching Posts

* Use search input to filter posts by title or content

---

## Configuration

### File Storage

Uploaded images are stored in:

```
storage/app/public/posts
```

To change disk settings, update:

```
config/filesystems.php
```

### Pagination

Default pagination is 6 posts per page.

Change in `PostController@index`:

```php
Post::latest()->paginate(6);
```

### Image Validation

```php
'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
```

---

## Deployment

### Production Steps

* Set up server (Apache/Nginx)
* Clone repository
* Install dependencies:

```bash
composer install --optimize-autoloader --no-dev
```

* Set permissions:

```bash
chmod -R 755 storage bootstrap/cache
```

* Configure `.env`
* Generate key:

```bash
php artisan key:generate --force
```

* Run migrations:

```bash
php artisan migrate --force
```

* Create storage link:

```bash
php artisan storage:link
```

---

## Learning Outcomes

* Laravel MVC architecture
* CRUD operations
* File upload handling
* Pagination and search
* Clean Blade templating
* Deployment basics

---

## License

This project is open-source and free to use for learning and development.

