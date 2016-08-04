<?php
/*
Plugin Name: Rooftop CMS - Request Parser
Description: Manipulate the REST request or server variables
Version: 0.0.1
Author: Error Studio
Author URI: http://errorstudio.co.uk
Plugin URI: http://errorstudio.co.uk
Text Domain: rooftop-request-parser
*/

/**
 * Change the maximum per_page collection arg to 99999999 (effectively removing the limit)
 */
add_action( 'rest_api_init', function() {
    global $wp_rest_server;

    /**
     * The 'endpoints' property is protected; use reflection to get the property, mutate it, then re-set it
     */
    $reflection = new ReflectionClass( $wp_rest_server );
    $endpoints_property = $reflection->getProperty('endpoints');
    $endpoints_property->setAccessible( true );
    $endpoints = $endpoints_property->getValue( $wp_rest_server );

    foreach( $endpoints as $endpoint => $resource ) {
        foreach( $resource as $object => $params ) {
            if( $object == 'args' && isset( $params['args']['per_page'] ) ) {
                $endpoints[$endpoint][$object]['args']['per_page']['maximum'] = 99999999;
            }
        }
    }

    $endpoints_property->setValue( $wp_rest_server, $endpoints );
}, 11 );
?>
