# Task Management System — Laravel 12 + Inertia + Vue 3 + TailwindCSS

A modern Task Management System built with **Laravel 12**, **Inertia.js**, **Vue 3**, **Font Awesome** and **Tailwind CSS**, following the **Repository Pattern**.  
Includes **Authentication**, **Role-based Access Control (ACL)**, **RESTful API (Laravel Sanctum)**, and **Background Jobs** for email notifications.

---

## Features

-  User Authentication (Sanctum for API)
- Role Management (Admin / User)
- Task CRUD (Create, Read, Update, Delete)
- Repository Pattern for clean architecture
- Filtering by Task Status (Complete / Incomplete)
- Access Control:
  - All users can view all tasks
  - Users can edit/delete only their own tasks
  - Admins can manage all tasks
- Email Notification (via Laravel Queues)
- RESTful API
- Unit Test

---

## Requirements

- PHP >= 8.3  
- Composer  
- Node.js v22.16.0 & NPM v10.9.2
- MySQL  
- Laravel 12  
---

## Installation & Setup

### Clone the Repository
git clone https://github.com/SM-Taiser/task-management-system.git
cd task-management-system ```

### Create Local Domain
Cretae a local domain (task.local) in your device. Also this domain need to update in vite.config.ts file..
```js
server {
    host: 'task.local'
}
```

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
APP_NAME=TaskManagement
APP_URL=http://task.local

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

### RESTful API Endpoints (Sanctum)

#### Authentication
| Method | Endpoint      | Description                |
| ------ | ------------- | -------------------------- |
| `POST` | `/api/login`  | Login user (returns token) |
| `POST` | `/api/register` | register user               

**Payload for Registration**
```js
{
   "name": 'user name'
   "email": 'admin@gmail.com'
   "password": 123456
   "c_password": 123456
}
```
**Payload for Login**
```js
{
   "email": 'admin@gmail.com'
   "password": 123456
}
```

#### Task
| Method   | Endpoint          | Description        |
| -------- | ----------------- | ------------------ |
| `GET`    | `/api/tasks`      | Get all tasks      |
| `POST`   | `/api/tasks`      | Create new task    |
| `PUT`    | `/api/tasks/{id}` | Update task        |
| `DELETE` | `/api/tasks/{id}` | Delete task        |

**Payload for Task creation and update**
```js
{
	"title": "Test title",
    "description": "Test",
    "status": "Complete"
}
```

➡ Check your email output in storage/logs/laravel.log.

## Unit Testing

```js
php artisan test tests/Unit/TaskTest.php
```
### Scalability Considerations

This Task Management System is designed for small-to-medium workloads, but as usage grows, the following scalability challenges may arise, along with possible solutions:

***Database Growth***

With thousands of users and tasks, queries like filtering tasks by status, or fetching all tasks for admin users, may become slow.
- Add proper indexes on frequently queried columns (e.g., user_id, status).
- Use pagination for task lists instead of loading all records.
- Consider database sharding or replication for very large datasets.

***Concurrent Requests***

High traffic may lead to slow response times for API or Inertia requests.
- Use caching for repeated queries (e.g., Redis).
- Deploy load balancers with multiple app servers.
- Optimize Eloquent queries and eager load relationships to prevent N+1 issues.

***Background Jobs***
  
Sending emails for every task creation/completion may overwhelm the queue if usage spikes.
- Scale queue workers horizontally.
- Use job batching or delayed jobs for bulk notifications.
- Consider third-party email services like Mailgun or SES for high throughput.

***API Usage***

Frequent API requests from multiple clients may overload the server.
- Implement rate limiting per user or API token.
- Optimize API responses (select only necessary fields).
- Introduce API caching for commonly requested endpoints.

***Frontend Scalability***

Large task lists may slow down rendering in the Inertia.js frontend.
- Use lazy loading or infinite scroll for task lists.
- Filter tasks server-side instead of rendering all tasks at once.
- Minimize JavaScript bundle size using code splitting.
