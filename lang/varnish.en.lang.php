<?php
/**
 * English language file for Varnish Cache Plugin
 *
 * @package Varnish
 * @copyright (c) Ali Çömez / Rootali
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

$L['varnish_name'] = 'Varnish Cache';
$L['varnish_desc'] = 'Varnish Cache integration for Cotonti';
$L['varnish_help'] = 'This plugin integrates Cotonti with Varnish Cache to improve performance.';

// Configuration
$L['varnish_status'] = 'Status';
$L['varnish_enabled'] = 'Enabled';
$L['varnish_reachable'] = 'Varnish reachable';
$L['varnish_cloudpanel_detected'] = 'CloudPanel Varnish detected';
$L['varnish_host'] = 'Varnish host';
$L['varnish_port'] = 'Varnish HTTP port';
$L['varnish_admin_port'] = 'Varnish admin port';
$L['varnish_server_port'] = 'Varnish server port';
$L['varnish_secret'] = 'Varnish secret';
$L['varnish_ttl'] = 'Default TTL';
$L['varnish_seconds'] = 'seconds';
$L['varnish_cache_tag_prefix'] = 'Cache tag prefix';
$L['varnish_excluded_params'] = 'Excluded GET parameters';
$L['varnish_exclude_admin'] = 'Exclude admin pages';
$L['varnish_exclude_users'] = 'Exclude logged-in users';
$L['varnish_purge_on_update'] = 'Purge on update';
$L['varnish_debug_mode'] = 'Debug mode';
$L['varnish_config'] = 'Configure plugin settings';
$L['varnish_edit_config'] = 'Edit plugin configuration';

// Purge URL
$L['varnish_purge_url'] = 'Purge specific URL';
$L['varnish_url'] = 'URL to purge';
$L['varnish_purge'] = 'Purge URL';
$L['varnish_purge_success'] = 'URL purged successfully';
$L['varnish_purge_error'] = 'Failed to purge URL';

// Purge Tag
$L['varnish_purge_tag'] = 'Purge by cache tag';
$L['varnish_tag'] = 'Cache tag';
$L['varnish_purge_tag_button'] = 'Purge by tag';
$L['varnish_purge_tag_success'] = 'Cache purged successfully by tag';
$L['varnish_purge_tag_error'] = 'Failed to purge cache by tag';
$L['varnish_purge_tag_help'] = 'Purge cache by tag allows you to invalidate specific parts of the cache without purging everything.';
$L['varnish_common_tags'] = 'Common cache tags';
$L['varnish_tag_all'] = 'All cached content';
$L['varnish_tag_page'] = 'All pages module content';
$L['varnish_tag_forums'] = 'All forums module content';
$L['varnish_tag_page_id'] = 'Specific page with ID 123';
$L['varnish_tag_forums_section'] = 'Specific forum section with ID 5';
$L['varnish_tag_forums_topic'] = 'Specific forum topic with ID 42';

// Purge all
$L['varnish_purge_all'] = 'Purge entire cache';
$L['varnish_purge_all_help'] = 'This will purge the entire Varnish cache. Use with caution on high-traffic sites.';
$L['varnish_purge_all_confirm'] = 'Are you sure you want to purge the entire cache?';
$L['varnish_purge_all_success'] = 'Entire cache purged successfully';
$L['varnish_purge_all_error'] = 'Failed to purge entire cache';

// Help
$L['varnish_help_title'] = 'Help & Information';
$L['varnish_help_text'] = 'Varnish Cache is a powerful HTTP accelerator that can significantly speed up your website. This plugin integrates Cotonti with Varnish Cache to provide optimal performance.';
$L['varnish_vcl_title'] = 'VCL Configuration';
$L['varnish_vcl_text'] = 'A sample VCL configuration file is included in the plugin directory (vcl-example.txt). Copy this file to your Varnish server and adjust it according to your needs.';
$L['varnish_cloudpanel_title'] = 'CloudPanel Integration';
$L['varnish_cloudpanel_text'] = 'This plugin is fully compatible with CloudPanel\'s Varnish Cache settings. The default settings match CloudPanel\'s recommended configuration, but you can customize them as needed.';

$L['cfg_enabled'] = 'Enable Varnish integration';
$L['cfg_host'] = 'Varnish host';
$L['cfg_port'] = 'Varnish admin port';
$L['cfg_secret'] = 'Varnish secret (leave empty if not set)';
$L['cfg_ttl'] = 'Default TTL for cached pages (seconds)';
$L['cfg_exclude_urls'] = 'URLs to exclude from caching (one per line, supports wildcards)';
$L['cfg_exclude_admin'] = 'Exclude admin pages from caching';
$L['cfg_exclude_users'] = 'Exclude logged-in users from caching';
$L['cfg_purge_on_update'] = 'Purge cache when content is updated';
$L['cfg_debug_mode'] = 'Debug mode (log purge requests)';

// Remove old strings
unset($L['varnish_purge_all_btn']);
unset($L['varnish_confirm_purgeall']);
unset($L['varnish_msg_purge_success']);
unset($L['varnish_msg_purge_failed']);
unset($L['varnish_msg_purgeall_success']);
unset($L['varnish_msg_purgeall_failed']); 
