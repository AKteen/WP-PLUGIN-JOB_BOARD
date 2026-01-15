# Job Board Plugin

A production-ready WordPress plugin for creating and managing job listings with filtering capabilities and REST API support.

## Features

- **Custom Post Type**: Dedicated job post type with full WordPress editor support
- **Taxonomies**: Job Type (Internship, Full-Time, Contract) and Location (Remote, Pune, Bangalore)
- **Custom Fields**: Company name, salary, and application URL
- **Frontend Display**: Shortcode with filtering by job type and location
- **REST API**: JSON endpoint for external integrations
- **Security**: Nonce verification, input sanitization, output escaping, and capability checks
- **Extensibility**: Custom action and filter hooks

## Installation

1. Upload the `job-board-plugin` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to 'Jobs' in the admin menu to start adding jobs

## Usage

### Admin

1. Go to **Jobs > Add New** in WordPress admin
2. Enter job title and description
3. Fill in the Job Details meta box:
   - Company Name
   - Salary
   - Apply URL
4. Select Job Type and Location taxonomies
5. Publish the job

### Frontend

Add the shortcode to any page or post:

```
[job_board]
```

Users can filter jobs by type and location using the dropdown filters.

### REST API

Access job data via:

```
GET /wp-json/my_job_board/v1/jobs
```

Returns JSON array with all published jobs including metadata and taxonomies.

## Developer Hooks

### Actions

```php
// Fires after job meta is saved
do_action( 'my_job_board_after_job_save', $post_id );
```

### Filters

```php
// Modify job title display
apply_filters( 'my_job_board_job_title', $title, $post_id );
```

## Architecture

- **OOP Design**: Clean class-based architecture
- **Separation of Concerns**: Admin, Public, and Core classes
- **WordPress Standards**: Follows WordPress PHP coding standards
- **Security First**: All inputs sanitized, outputs escaped, nonces verified
- **No Globals**: Zero global variables used

## File Structure

```
job-board-plugin/
├── job-board.php                           # Main plugin file
├── uninstall.php                           # Cleanup on uninstall
├── includes/
│   ├── class-my-job-board.php             # Core plugin class
│   ├── class-my-job-board-activator.php   # Activation handler
│   ├── class-my-job-board-deactivator.php # Deactivation handler
│   └── class-my-job-board-rest-api.php    # REST API endpoints
├── admin/
│   └── class-my-job-board-admin.php       # Admin functionality
└── public/
    └── class-my-job-board-public.php      # Frontend functionality
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Uninstallation

When the plugin is deleted, all data is automatically removed:
- All job posts
- All taxonomy terms
- All post meta
- All plugin options

## License

GPL-2.0+

