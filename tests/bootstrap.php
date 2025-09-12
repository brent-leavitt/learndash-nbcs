<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Doula_Course
 */

// First, we need to load the composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Initialize WP_Mock
WP_Mock::setUsePatchwork( true );
WP_Mock::bootstrap();

// Define WordPress constants
define('ABSPATH', true);
define('WP_DEBUG', true);

/**
 * Set up WP_Mock for each test
 */
class NBCS_TestCase extends WP_Mock\Tools\TestCase {
    public function setUp(): void {
        WP_Mock::setUp();
    }

    public function tearDown(): void {
        WP_Mock::tearDown();
    }
}
