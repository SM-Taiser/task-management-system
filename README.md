# 🧩 Task Management System — Laravel 12 + Inertia + Vue 3 + TailwindCSS

A modern Task Management System built with **Laravel 12**, **Inertia.js**, **Vue 3**, and **Tailwind CSS**, following the **Repository Pattern**.  
Includes **Authentication**, **Role-based Access Control (ACL)**, **RESTful API (Laravel Sanctum)**, and **Background Jobs** for email notifications.

---

## 🚀 Features

- 🧑‍💻 User Authentication (Sanctum for API)
- 🔑 Role Management (Admin / User)
- 🗂️ Task CRUD (Create, Read, Update, Delete)
- ⚙️ Repository Pattern for clean architecture
- 🎯 Filtering by Task Status (Complete / Incomplete)
- 🔒 Access Control:
  - All users can view all tasks
  - Users can edit/delete only their own tasks
  - Admins can manage all tasks
- 📬 Email Notification (via Laravel Queues)
- 🔌 RESTful API
- 🧪 Unit Test

---

## 🧰 Requirements

- PHP >= 8.3  
- Composer  
- Node.js v22.16.0 & NPM v10.9.2
- MySQL  
- Laravel 12  
---

## ⚙️ Installation & Setup

### Clone the Repository
git clone https://github.com/SM-Taiser/task-management-system.git
cd task-management-system ```

### Install Dependencies
- **composer install**
- **npm install && npm run dev**
  
### Environment Setup

Copy .env.example → .env and update your database and mail credentials:

```js
cp .env.example .env
 ```
Example .env settings:
```js
APP_NAME=Task Management
APP_URL=http://lara-12.local

DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log
QUEUE_CONNECTION=database
```
### Generate Keys
```js
php artisan key:generate
```
### Run migration and seeder
```js
 php artisan migrate
```
```js
 php artisan db:seed
```

This will create:

**Admin user:** admin@gmail.com / 123456

**Normal user:** user@gmail.com / 123456

### Start Queue Worker

```js
php artisan queue:work
```

➡ Check your email output in storage/logs/laravel.log.

