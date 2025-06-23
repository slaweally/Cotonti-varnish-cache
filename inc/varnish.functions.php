<?php
/**
 * Varnish Cache Plugin Functions
 *
 * @package Varnish
 * @copyright (c) Ali Çömez / Rootali
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

/**
 * Returns Varnish Cache singleton instance
 * @return VarnishCache
 */
function cot_varnish_instance()
{
    global $varnishCache;
    
    if (!isset($varnishCache)) {
        require_once Cot::$cfg['plugins_dir'] . '/varnish/varnish.php';
    }
    
    return $varnishCache;
}

/**
 * Purge URL from Varnish cache
 * @param string $url URL to purge
 * @return bool Success
 */
function cot_varnish_purge_url($url)
{
    $varnishCache = VarnishCache::getInstance();
    return $varnishCache->purgeUrl($url);
}

/**
 * Purge all cache
 * @return bool Success
 */
function cot_varnish_purge_all()
{
    $varnishCache = VarnishCache::getInstance();
    return $varnishCache->purgeAll();
}

/**
 * Purge cache by tag
 * @param string $tag Cache tag
 * @return bool Success
 */
function cot_varnish_purge_by_tag($tag)
{
    $varnishCache = VarnishCache::getInstance();
    return $varnishCache->purgeByTag($tag);
}

/**
 * Check if current URL should be excluded from caching
 * @return bool
 */
function cot_varnish_should_exclude()
{
    $varnishCache = VarnishCache::getInstance();
    return $varnishCache->shouldExclude();
}

/**
 * Set cache headers for the current response
 */
function cot_varnish_set_headers()
{
    $varnishCache = VarnishCache::getInstance();
    $varnishCache->setCacheHeaders();
}

/**
 * Get Varnish status
 * @return array Status information
 */
function cot_varnish_get_status()
{
    global $cfg;
    
    $status = array(
        'enabled' => (bool)$cfg['plugin']['varnish']['enabled'],
        'host' => $cfg['plugin']['varnish']['host'],
        'port' => (int)$cfg['plugin']['varnish']['port'],
        'admin_port' => (int)$cfg['plugin']['varnish']['admin_port'],
        'server_port' => (int)$cfg['plugin']['varnish']['server_port'],
        'ttl' => (int)$cfg['plugin']['varnish']['ttl'],
        'cache_tag_prefix' => $cfg['plugin']['varnish']['cache_tag_prefix'],
        'excluded_params' => $cfg['plugin']['varnish']['excluded_params'],
        'exclude_admin' => (bool)$cfg['plugin']['varnish']['exclude_admin'],
        'exclude_users' => (bool)$cfg['plugin']['varnish']['exclude_users'],
        'purge_on_update' => (bool)$cfg['plugin']['varnish']['purge_on_update'],
        'debug_mode' => (bool)$cfg['plugin']['varnish']['debug_mode'],
    );
    
    // Check if Varnish is reachable
    $status['reachable'] = false;
    
    if ($status['enabled']) {
        // Try to detect CloudPanel Varnish
        $cloudpanel_detected = ($status['cache_tag_prefix'] === '55f8');
        
        // Determine which port to check
        $check_port = $status['port'];
        if ($cloudpanel_detected && (!$check_port || $check_port == 0)) {
            $check_port = 80; // Default CloudPanel HTTP port
        }
        
        // If port is still not set, use server_port
        if (!$check_port || $check_port == 0) {
            $check_port = $status['server_port'];
            if (!$check_port || $check_port == 0) {
                $check_port = 6081; // Default Varnish port
            }
        }
        
        // Try to connect to Varnish
        $fp = @fsockopen($status['host'], $check_port, $errno, $errstr, 1);
        if ($fp) {
            $status['reachable'] = true;
            fclose($fp);
        } else {
            // Try alternative port if first attempt failed
            $alt_port = ($check_port == 80) ? 6081 : 80;
            $fp = @fsockopen($status['host'], $alt_port, $errno, $errstr, 1);
            if ($fp) {
                $status['reachable'] = true;
                fclose($fp);
            }
        }
    }
    
    return $status;
} 
