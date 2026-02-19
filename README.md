# TickLyst - Task Management System

[![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-orange)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)](https://getbootstrap.com/)

TickLyst is a modern, portfolio-ready task management web application built with PHP 8+ (vanilla MVC architecture), MySQL, and Bootstrap 5. It provides a clean, intuitive interface for managing projects and tasks with reminders, filters, and soft-delete functionality.

## Features

### Core Features
- **Authentication System**: Secure registration, login, and logout with PHP sessions
- **Password Security**: Using `password_hash()` and `password_verify()`
- **CSRF Protection**: All POST forms are protected against CSRF attacks
- **Access Control**: User-scoped data - users only see their own projects and tasks

### Project Management
- Create, read, update, and delete projects
- Color-coded projects for easy identification
- Project progress tracking with completion percentage
- Task count per project

### Task Management
- Full CRUD operations for tasks
- Task fields:
  - Title (required) and description (optional)
  - Status: Todo, In Progress, Done
  - Priority: High (1), Medium (2), Low (3)
  - Due date (optional)
  - Reminder date/time (optional)
  - Timestamps: created_at, updated_at
- Soft delete with trash functionality
- Restore or permanently delete tasks from trash
- AJAX-powered task status toggle (done/undone) without page reload

### Dashboard & Views
- **Dashboard**: Today's tasks, Overdue tasks, Upcoming tasks (next 7 days)
- **Projects View**: Kanban-style board (Todo, In Progress, Done columns)
- **All Tasks View**: Filterable and searchable task list
- **Notifications**: In-app reminders for tasks with remind_at <= now

### Filters & Search
- Filter by project
- Filter by status (todo/doing/done)
- Filter by priority (1-3)
- Text search across task titles and descriptions

### Additional Features
- **CLI Reminder Script**: Send email reminders via cron (stub implementation)
- Flash messages for user feedback
- Responsive Bootstrap 5 UI
- Clean, modern design
- Mobile-friendly interface

## Tech Stack

- **Backend**: PHP 8+ (vanilla, no framework)
- **Architecture**: MVC (Model-View-Controller)
- **Database**: MySQL 8.0+ with PDO
- **Frontend**: Bootstrap 5.3 + Bootstrap Icons
- **CSS**: Custom CSS (Sass-ready structure)
- **JavaScript**: Vanilla JS with Fetch API for AJAX

## Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server with mod_rewrite
- Composer (optional, not required for core functionality)

## Installation

### Local Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/Guillaume0402/TickyList.git
   cd TickyList
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` and set your database credentials:
   ```
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=ticklyst
   DB_USER=root
   DB_PASS=your_password
   ```

3. **Create database and import schema**
   ```bash
   mysql -u root -p
   ```
   
   Then run:
   ```sql
   CREATE DATABASE ticklyst CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE ticklyst;
   SOURCE database/schema.sql;
   SOURCE database/seed.sql;  -- Optional: Load demo data
   ```

4. **Configure web server**

   **Apache**: Point document root to `public/` directory
   
   Example Apache VirtualHost:
   ```apache
   <VirtualHost *:80>
       ServerName ticklyst.local
       DocumentRoot /path/to/TickyList/public
       
       <Directory /path/to/TickyList/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

   **PHP Built-in Server** (for development):
   ```bash
   cd public
   php -S localhost:8000
   ```

5. **Access the application**
   
   Open your browser and navigate to `http://localhost:8000` (or your configured domain)

### Demo Credentials

If you loaded the seed data:
- **Email**: demo@ticklyst.com
- **Password**: password123

## Directory Structure

```
TickyList/
├── app/
│   ├── Controllers/       # Application controllers
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ProjectController.php
│   │   ├── TaskController.php
│   │   └── NotificationController.php
│   ├── Core/             # Core framework components
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Auth.php
│   │   ├── CSRF.php
│   │   ├── Flash.php
│   │   └── helpers.php
│   ├── Models/           # Data models
│   │   ├── User.php
│   │   ├── Project.php
│   │   └── Task.php
│   └── Views/            # View templates
│       ├── layouts/
│       ├── auth/
│       ├── dashboard/
│       ├── projects/
│       ├── tasks/
│       └── notifications/
├── config/               # Configuration files
│   ├── env.php          # Environment loader
│   └── db.php           # Database connection
├── database/            # Database files
│   ├── schema.sql       # Database schema
│   └── seed.sql         # Demo data (optional)
├── public/              # Public web root
│   ├── index.php        # Front controller
│   ├── .htaccess
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── storage/             # Storage directory
│   └── logs/           # Application logs
├── sass/               # Sass source files (optional)
├── .env.example        # Environment template
├── .gitignore
├── reminder.php        # CLI reminder script
└── README.md
```

## Usage

### Managing Projects

1. **Create Project**: Click "New Project" button
2. **View Project**: Click on a project card to see its tasks
3. **Edit Project**: Click edit button on project page
4. **Delete Project**: Use delete button (warning: deletes all tasks)

### Managing Tasks

1. **Create Task**: Click "New Task" button
2. **Edit Task**: Click edit icon on any task
3. **Quick Toggle**: Check/uncheck task to mark as done/undone (AJAX)
4. **Delete Task**: Moves to trash (soft delete)
5. **Restore Task**: Go to Trash, click restore
6. **Permanent Delete**: In Trash, click "Delete Forever"

### Dashboard

- View overdue tasks (past due date, not done)
- View today's tasks (due today)
- View upcoming tasks (due in next 7 days)
- Quick project overview with completion stats

### Filters & Search

On the "All Tasks" page:
- Filter by project
- Filter by status
- Filter by priority
- Search by keyword in title/description

### Notifications

View tasks with active reminders (remind_at <= now and status != done)

## CLI Reminder Script

The `reminder.php` script can be run via cron to send email reminders:

```bash
# Run manually
php reminder.php

# Add to crontab (run every hour)
0 * * * * /usr/bin/php /path/to/TickyList/reminder.php >> /path/to/TickyList/storage/logs/cron.log 2>&1
```

**Note**: The email functionality is currently a stub. To enable actual email sending:
1. Install PHPMailer: `composer require phpmailer/phpmailer`
2. Configure SMTP settings in `.env`
3. Update the `sendReminderEmail()` function in `reminder.php`

## Security Features

- **Password Hashing**: Using PHP's `password_hash()` with default algorithm
- **CSRF Protection**: All state-changing operations require CSRF token
- **SQL Injection Prevention**: Using PDO prepared statements throughout
- **XSS Prevention**: All output is escaped via `htmlspecialchars()`
- **Session Security**: Regenerate session ID on login
- **Access Control**: User data isolation at database level

## Development

### Code Style

- PSR-12 compliant PHP code
- Clear, descriptive variable and function names
- Comments for complex logic
- English language for all code and comments

### Database

- All tables use InnoDB engine
- Foreign key constraints for referential integrity
- Indexed columns for performance
- UTF-8 character set (utf8mb4)

### Frontend

- Bootstrap 5.3 for UI components
- Custom CSS for styling
- Vanilla JavaScript (no jQuery dependency)
- Fetch API for AJAX requests

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Contributing

This is a portfolio/educational project. Feel free to fork and customize for your needs.

## License

MIT License - feel free to use for personal or commercial projects.

## Author

Created as a portfolio project demonstrating PHP MVC architecture, MySQL database design, and modern web development practices.

## Roadmap

Potential future enhancements:
- [ ] User profile management
- [ ] Task comments/notes
- [ ] File attachments
- [ ] Team collaboration features
- [ ] Task templates
- [ ] Calendar view
- [ ] Export to CSV/PDF
- [ ] Email notifications (production-ready)
- [ ] Dark mode
- [ ] API endpoints for mobile app

## Support

For issues or questions, please open an issue on GitHub.
