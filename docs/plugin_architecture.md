# LearnDash NBCS Plugin Architecture Documentation

## Overview

### Basic Information
- **Name:** LearnDash for New Beginnings Childbirth Services
- **Slug:** learndash-nbcs
- **Version:** 2.0
- **Author:** Brent Leavitt
- **Dependencies:** LearnDash LMS, Restrict Content Pro

### Related Documentation
- [LearnDash Integration](./learndash_integration_md.md) - Details on LearnDash LMS integration points
- [RCP Integration](./rcp_integration_md.md) - Restrict Content Pro integration details
- [Business Logic](./business_logic_md.md) - Core business rules and workflows
- [Filters & Hooks Reference](./learndash-nbcs-filters-and-hooks.md) - Complete API reference

### Development Requirements
- PHP 7.4+
- WordPress 5.8+
- LearnDash LMS 4.0+
- Restrict Content Pro 3.0+

## Plugin Structure & Organization

### File Structure
```
learndash-nbcs/
├── learndash-nbcs.php          # Main plugin entry point
├── app/                        # Application code
│   ├── clss/                  # Class definitions
│   │   ├── admin_page/       # Admin interface classes
│   │   ├── forms/           # Form handling classes
│   │   ├── grades/         # Grading system classes
│   │   ├── interfaces/     # Interface definitions
│   │   ├── processors/     # Data processing classes
│   │   ├── registrar/     # Registration handling
│   │   ├── shortcode/     # Shortcode implementations
│   │   ├── tables/        # Database table definitions
│   │   └── user_page/     # User interface classes
│   ├── func/                  # Functional components
│   │   ├── admin_func.php     # Admin functionality
│   │   ├── non_admin.php      # Frontend functionality
│   │   └── requires.php       # Dependency management
│   └── tmpl/                  # Template files
└── docs/                      # Documentation

```

### Namespace & Prefix
- Main Namespace: `Doula_Course`
- Constants Prefix: `NBCS_`
- Database Prefix: `nbcs_`

## Core Components

### Custom Post Types
1. Assignment CPT
   - Used for student assignment submissions
   - Custom statuses: Draft, Submitted, Incomplete, Complete
   - Integrated with LearnDash content

### Integration Architecture

#### Available Hooks and Filters

##### Actions
```php
// Course Progress
do_action('nbcs_before_progress_update', $student_id, $course_id);
do_action('nbcs_after_progress_update', $student_id, $course_id, $progress);

// Assignment Workflow
do_action('nbcs_before_assignment_submission', $assignment_id, $student_id);
do_action('nbcs_after_assignment_submission', $assignment_id, $student_id);
do_action('nbcs_assignment_status_changed', $assignment_id, $old_status, $new_status);

// Trainer Management
do_action('nbcs_trainer_assignment_changed', $student_id, $old_trainer_id, $new_trainer_id);
do_action('nbcs_trainer_feedback_submitted', $assignment_id, $trainer_id);

// Assignment Status Hooks
do_action('nb_assignment_submitted', $assignment_id, $student_id);
do_action('nb_assignment_resubmitted', $assignment_id, $student_id);
do_action('nb_assignment_completed', $assignment_id, $student_id);
do_action('nb_assignment_incomplete', $assignment_id, $student_id);

// Trainer Assignment Hooks
do_action('nb_trainer_reassignment', $student_id, $old_trainer_id, $new_trainer_id);
do_action('nb_trainer_new_student', $student_id, $trainer_id);
```

##### Filters
```php
// Status Management
apply_filters('nbcs_assignment_status_labels', $status_labels);
apply_filters('nbcs_valid_status_transitions', $valid_transitions, $current_status);

// Access Control
apply_filters('nbcs_trainer_capability_check', $can_access, $trainer_id, $student_id);
apply_filters('nbcs_student_course_access', $has_access, $student_id, $course_id);

// Progress Calculation
apply_filters('nbcs_student_progress_calculation', $progress, $student_id);
apply_filters('nbcs_completion_requirements', $requirements, $course_id);
```

## Database Schema & Data Flow

### Meta Keys and Storage
```markdown
1. Assignment Meta
- `_nbcs_assignment_status` - Tracks submission status
- `_nbcs_trainer_id` - Assigned trainer
- `_nbcs_submission_date` - Latest submission timestamp
- `_nbcs_feedback_history` - JSON encoded feedback history

2. User Meta
- `_nbcs_assigned_trainer` - Current trainer ID
- `_nbcs_course_progress` - Custom progress tracking
- `_nbcs_certification_date` - Certification expiration
```

### Data Flow Architecture
The plugin implements a structured data flow system through dedicated processor classes:
- `Assignment_Processor`: Handles assignment submissions
- `Student_Progress`: Manages progress tracking
- `Submission`: Processes form submissions

## Admin Interfaces

### Main Admin Menu Structure
- NBCS Doula Training
  - Dashboard
  - Students
  - Trainers 
  - Assignments
  - Settings

### Role Management
The plugin implements custom roles with specific capabilities:
- `nbcs_trainer`: Can grade assignments and manage students
- `nbcs_student`: Can submit assignments and view course content

## Frontend Components

### Template System
Located in `app/tmpl/`:
- course-content.php: Main course display
- course-assignment.php: Assignment submission form
- course-overview.php: Student progress dashboard
- page-progress-report.php: Detailed progress view
- page-profile-editor.php: Student profile management

### Shortcodes
```markdown
- [nbcs_progress]: Displays student progress bar
- [nbcs_assignments]: Lists pending/completed assignments
- [nbcs_trainer]: Shows assigned trainer info
- [nbcs_profile]: Renders student profile editor
```

## Current Architecture Challenges

### Performance Issues
1. Query Optimization Needed
   - Assignment queries not properly cached
   - Multiple unnecessary meta queries in progress calculations
   - Large trainer-student relationship queries need optimization

2. Identified Bottlenecks
   - Progress calculation recalculates too frequently
   - Multiple database calls for status updates

### Code Organization Issues
1. Areas Needing Refactoring
   - Some classes have too many responsibilities
   - Better separation of concerns needed
   - Template system needs hierarchy improvement

2. Legacy Code
   - Multiple archived files need review
   - Old functionality needs proper deprecation

### Integration Pain Points
1. LearnDash Integration
   - Progress sync issues
   - Complex content access control
   - Performance impact from multiple permission checks

2. Restrict Content Pro Integration
   - Subscription status change handling
   - Access level sync automation needed

## Recommended Solutions

### Short Term
1. Implement caching for:
   - Progress calculations
   - Assignment status queries
   - Trainer-student relationships

2. Consolidate database queries
   - Batch update operations
   - Use WordPress cache API

### Long Term
1. Architecture Updates
   - Implement proper dependency injection
   - Create interface contracts
   - Build proper testing framework

2. Modern Development Practices
   - Add unit tests
   - Implement CI/CD pipeline
   - Better documentation system

   ## Development & Deployment

### Development Guidelines
- Follow WordPress Coding Standards
- Use PHP_CodeSniffer with WordPress ruleset
- Run `composer lint` to check code style

### Testing Environment
- PHPUnit test suite in `/tests`
- Run tests: `composer test`
- Coverage reports: `/tests/coverage`
- Local development with Debug enabled
- Docker environment in `/docker`

### Version Control Workflow
1. Branch Strategy
   - `main`: Production releases
   - `develop`: Development branch
   - `feature/*`: New features
   - `hotfix/*`: Emergency fixes

2. Development Process
   - Create feature branch from `develop`
   - Develop and test new features
   - Submit pull request to `develop`
   - Code review and automated testing
   - Merge to `develop`
   - Release preparation
   - Merge to `main` for production

### Deployment Process
1. Release Steps
   - Version bump in `learndash-nbcs.php`
   - Update changelog
   - Run full test suite
   - Build production assets
   - Create release tag
   - Deploy to production

2. Automation
   - GitHub Actions for CI/CD
   - Automated testing on pull requests
   - Release automation scripts
   - Production deployment checks
- [LearnDash Integration](./learndash_integration_md.md) - Details on LearnDash LMS integration points
- [RCP Integration](./rcp_integration_md.md) - Restrict Content Pro integration details
- [Business Logic](./business_logic_md.md) - Core business rules and workflows
- [Filters & Hooks Reference](./learndash-nbcs-filters-and-hooks.md) - Complete API reference

## API Reference

### Actions
```php
// Course Progress
do_action('nbcs_before_progress_update', $student_id, $course_id);
do_action('nbcs_after_progress_update', $student_id, $course_id, $progress);

// Assignment Workflow
do_action('nbcs_before_assignment_submission', $assignment_id, $student_id);
do_action('nbcs_after_assignment_submission', $assignment_id, $student_id);
do_action('nbcs_assignment_status_changed', $assignment_id, $old_status, $new_status);

// Trainer Management
do_action('nbcs_trainer_assignment_changed', $student_id, $old_trainer_id, $new_trainer_id);
do_action('nbcs_trainer_feedback_submitted', $assignment_id, $trainer_id);
```

### Filters
```php
// Status Management
apply_filters('nbcs_assignment_status_labels', $status_labels);
apply_filters('nbcs_valid_status_transitions', $valid_transitions, $current_status);

// Access Control
apply_filters('nbcs_trainer_capability_check', $can_access, $trainer_id, $student_id);
apply_filters('nbcs_student_course_access', $has_access, $student_id, $course_id);

// Progress Calculation
apply_filters('nbcs_student_progress_calculation', $progress, $student_id);
apply_filters('nbcs_completion_requirements', $requirements, $course_id);
```

## Development Guidelines

### Testing
- PHPUnit test suite location: `/tests`
- Run tests: `composer test`
- Coverage reports: `/tests/coverage`

### Development Environment
- Required: PHP 7.4+, WordPress 5.8+
- Recommended: Local development with Debug enabled
- Docker environment available in `/docker`

### Coding Standards
- Follow WordPress Coding Standards
- Use PHP_CodeSniffer with WordPress ruleset
- Run `composer lint` to check code style

## Version Control

### Branch Strategy
- `main`: Production releases
- `develop`: Development branch
- `feature/*`: New features
- `hotfix/*`: Emergency fixes

### Git Workflow
1. Create feature branch from `develop`
2. Develop and test new features
3. Submit pull request to `develop`
4. Code review and automated testing
5. Merge to `develop`
6. Release preparation
7. Merge to `main` for production

## Deployment

### Release Process
1. Version bump in `learndash-nbcs.php`
2. Update changelog
3. Run full test suite
4. Build production assets
5. Create release tag
6. Deploy to production

### Automation
- GitHub Actions for CI/CD
- Automated testing on pull requests
- Release automation scripts
- Production deployment checks
