<?php
/**
 * Varnish Cache Plugin Admin
 *
 * @package Varnish
 * @copyright (c) Ali Çömez / Rootali
 * @license BSD
 */

/* ====================
[BEGIN_COT_EXT]
Hooks=tools
Order=10
Tags=
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('varnish', 'plug');

$adminTitle = $L['varnish_name'];
$adminHelp = $L['varnish_help'];

// Get Varnish status
$varnishStatus = cot_varnish_get_status();

// Handle actions
$action = cot_import('action', 'G', 'ALP');

// Handle URL purge
if ($action === 'purge') {
    cot_check_xg();  // Check CSRF token
    $url = cot_import('url', 'G', 'TXT');
    if ($url) {
        $success = cot_varnish_purge_url($url);
        if ($success) {
            cot_message($L['varnish_purge_success'], 'ok');
        } else {
            cot_message($L['varnish_purge_error'], 'error');
        }
    }
}
// Handle tag purge
elseif ($action === 'purge_tag') {
    cot_check_xg();  // Check CSRF token
    $tag = cot_import('tag', 'G', 'TXT');
    if ($tag) {
        $success = cot_varnish_purge_by_tag($tag);
        if ($success) {
            cot_message($L['varnish_purge_tag_success'], 'ok');
        } else {
            cot_message($L['varnish_purge_tag_error'], 'error');
        }
    }
}
// Handle purge all
elseif ($action === 'purge_all') {
    cot_check_xg();  // Check CSRF token
    $success = cot_varnish_purge_all();
    if ($success) {
        cot_message($L['varnish_purge_all_success'], 'ok');
    } else {
        cot_message($L['varnish_purge_all_error'], 'error');
    }
}

// Prepare template
$t = new XTemplate(cot_tplfile('varnish.admin', 'plug'));

// Check if we're using CloudPanel Varnish
$cloudpanel_varnish = false;
$cloudpanel_host = '127.0.0.1';
$cloudpanel_port = 6081;
$cloudpanel_admin_port = 6082;
$cloudpanel_tag_prefix = '55f8';

// If Varnish host contains port, extract it
if (strpos($varnishStatus['host'], ':') !== false) {
    list($host, $port) = explode(':', $varnishStatus['host']);
    $varnishStatus['host'] = $host;
    if (empty($varnishStatus['server_port']) || $varnishStatus['server_port'] == 0) {
        $varnishStatus['server_port'] = (int)$port;
    }
}

// If CloudPanel is detected, update the status
if ($varnishStatus['cache_tag_prefix'] === '55f8') {
    $cloudpanel_varnish = true;
    $cloudpanel_tag_prefix = $varnishStatus['cache_tag_prefix'];
    
    // Set default CloudPanel ports if not set
    if (empty($varnishStatus['port']) || $varnishStatus['port'] == 0) {
        $varnishStatus['port'] = 80;
    }
    if (empty($varnishStatus['server_port']) || $varnishStatus['server_port'] == 0) {
        $varnishStatus['server_port'] = 6081;
    }
    if (empty($varnishStatus['admin_port']) || $varnishStatus['admin_port'] == 0) {
        $varnishStatus['admin_port'] = 6082;
    }
}

// Pass status to template
$t->assign(array(
    'VARNISH_ENABLED' => $varnishStatus['enabled'],
    'VARNISH_HOST' => $varnishStatus['host'],
    'VARNISH_PORT' => $varnishStatus['port'],
    'VARNISH_ADMIN_PORT' => $varnishStatus['admin_port'],
    'VARNISH_SERVER_PORT' => $varnishStatus['server_port'],
    'VARNISH_TTL' => $varnishStatus['ttl'],
    'VARNISH_CACHE_TAG_PREFIX' => $varnishStatus['cache_tag_prefix'],
    'VARNISH_EXCLUDED_PARAMS' => !empty($varnishStatus['excluded_params']) ? $varnishStatus['excluded_params'] : '__SID,noCache',
    'VARNISH_EXCLUDE_ADMIN' => $varnishStatus['exclude_admin'],
    'VARNISH_EXCLUDE_USERS' => $varnishStatus['exclude_users'],
    'VARNISH_PURGE_ON_UPDATE' => $varnishStatus['purge_on_update'],
    'VARNISH_DEBUG_MODE' => $varnishStatus['debug_mode'],
    'VARNISH_REACHABLE' => $varnishStatus['reachable'],
    'VARNISH_CLOUDPANEL' => $cloudpanel_varnish,
    'VARNISH_PURGE_URL_FORM_ACTION' => cot_url('admin', 'm=other&p=varnish&action=purge&' . cot_xg()),
    'VARNISH_PURGE_TAG_FORM_ACTION' => cot_url('admin', 'm=other&p=varnish&action=purge_tag&' . cot_xg()),
    'VARNISH_PURGE_ALL_URL' => cot_url('admin', 'm=other&p=varnish&action=purge_all&' . cot_xg()),
    'VARNISH_CONFIG_URL' => cot_url('admin', 'c=plug&ext=varnish'),
));

// Output template
$t->parse('MAIN');
$adminMain = $t->text('MAIN'); 