<?php
/**
 * Theme Functions
 * 
 * Minimal bootstrap file - only autoloader and theme initialization
 * 
 * @package SpbShield
 * @since 2.1.0
 */


use SpbShield\Inc\Theme;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * PSR-4 Autoloader for theme classes
 * 
 * Automatically loads classes from the inc/ directory
 * 
 * @param string $class Class name with namespace
 */
spl_autoload_register(function ($class) {
    $prefix = 'SpbShield\\Inc\\';
    $base_dir = get_stylesheet_directory() . '/inc/';
    
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Initialize theme
 * 
 * Bootstrap the theme by creating an instance of the main Theme class
 */
new Theme();
