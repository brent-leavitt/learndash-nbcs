# LearnDash NBCS

![CI](https://github.com/brent-leavitt/learndash-nbcs/workflows/Continuous%20Integration/badge.svg)

LearnDash for New Beginnings Childbirth Services - A WordPress plugin extending LearnDash LMS functionality.

## Requirements
- PHP 7.4+
- WordPress 5.8+
- LearnDash LMS 4.0+
- Restrict Content Pro 3.0+

## Development

### Setup
```bash
# Install dependencies
composer install

# Run tests
composer test

# Check coding standards
composer phpcs
```

### Testing
Tests are written using PHPUnit and WP_Mock. Run tests using:
```bash
vendor/bin/phpunit
```

### Coding Standards
This project follows WordPress Coding Standards. Check your code using:
```bash
vendor/bin/phpcs
```

## Contributing
1. Create a feature branch from 'develop'
2. Make your changes
3. Run tests and coding standards checks
4. Submit a pull request

## License
GPL v2 or later