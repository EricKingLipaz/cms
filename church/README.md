# Church Management System

A comprehensive multi-branch church management system built with PHP, HTML, Tailwind CSS, and JavaScript.

## Features

### User Management
- Admin and Super Admin roles
- Branch-specific permissions
- User authentication and session management

### Member Management
- Track all church members across branches
- Store contact details, attendance, and participation records
- Member profile management

### Event Management
- Organize and schedule church events
- Track RSVPs and event attendance
- Event calendar and notifications

### Donation Tracking
- Record and manage donations
- Generate receipts and financial reports
- Track donation trends and patterns

### Communication Tools
- Send messages to members, groups, or entire congregation
- Message templates and scheduling
- Communication history tracking

### Resource Management
- Track church equipment, rooms, and properties
- Equipment maintenance scheduling
- Resource allocation and usage tracking

### Branch Management
- Centralized database for all church branches
- Headquarter and branch distinction
- Branch-specific data management

### Data Synchronization
- Real-time data syncing between branches
- Centralized data management
- Conflict resolution mechanisms

### Reporting and Analytics
- Comprehensive dashboards
- Membership growth reports
- Financial analytics
- Event participation statistics
- Resource utilization reports

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server
- Composer (for dependency management)

## Installation

1. Clone the repository to your web server directory
2. Create a MySQL database and import the SQL schema
3. Update the database configuration in `includes/dbconnection.php`
4. Ensure proper file permissions for upload directories
5. Access the system through your web browser

## Database Structure

The system uses a multi-branch database schema with the following key tables:

- `branches` - Church branch information
- `tblchristian` - Church members
- `church_events` - Church events and activities
- `donations` - Financial donations
- `church_equipment` - Church resources and equipment
- `messages` - Communication system
- `tbladmin` - System administrators

## User Roles

### Super Admin (Headquarters)
- Full access to all branches
- System configuration and management
- Global reporting and analytics
- User management across all branches

### Branch Admin
- Access limited to their assigned branch
- Member, event, and donation management
- Local reporting capabilities
- Resource management for their branch

## Modules

1. **Dashboard** - System overview and quick access
2. **Members** - Member management and profiles
3. **Events** - Event planning and management
4. **Donations** - Financial tracking and reporting
5. **Communications** - Messaging and notifications
6. **Resources** - Equipment and property management
7. **Branches** - Branch management (Super Admin only)
8. **Reports** - Analytics and reporting
9. **Sync** - Data synchronization tools

## Security Features

- Role-based access control
- Secure authentication system
- Data encryption for sensitive information
- SQL injection prevention
- Cross-site scripting (XSS) protection

## Technologies Used

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Database**: MySQL
- **Charting**: Chart.js
- **Icons**: Font Awesome

## API Endpoints

The system includes a RESTful API for data synchronization:

- `GET /sync/sync_api.php` - Retrieve data for synchronization
- `POST /sync/sync_api.php` - Send data for synchronization

## Testing

Integration tests are available in the `tests` directory to verify system functionality.

## Support

For issues and feature requests, please create an issue in the repository.

## License

This project is licensed under the MIT License - see the LICENSE file for details.