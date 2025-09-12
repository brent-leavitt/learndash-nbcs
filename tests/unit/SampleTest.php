<?php
namespace Doula_Course\Tests;

class SampleTest extends \NBCS_TestCase {
    
    public function test_sample() {
        // Set up expectations
        \WP_Mock::userFunction('is_admin')->once()->andReturn(true);
        
        // Verify true is returned when is_admin() is called
        $this->assertTrue(\is_admin());
        
        // Verify all expectations
        $this->assertConditionsMet();
    }
    
    public function test_constants_defined() {
        $this->assertTrue(defined('ABSPATH'), 'ABSPATH is not defined');
        $this->assertTrue(defined('WP_DEBUG'), 'WP_DEBUG is not defined');
    }
}
