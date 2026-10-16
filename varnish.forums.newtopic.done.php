<?php
/**
 * Varnish Cache Plugin Forums New Topic Done Hook
 *
 * @package Varnish
 * @copyright (c) Ali Çömez / Rootali
 * @license BSD
 */

/* ====================
[BEGIN_COT_EXT]
Hooks=forums.newtopic.newtopic.done
Order=10
Tags=
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

// Load Varnish class
require_once cot_incfile('varnish', 'plug');

// Check if purge on update is enabled
if (Cot::$cfg['plugin']['varnish']['purge_on_update']) {
    // Purge forum section URL and home page
    $varnishCache = VarnishCache::getInstance();
    $varnishCache->purgeUrl(cot_url('forums', 'm=topics&s=' . $s, '', true));
    $varnishCache->purgeUrl(cot_url('forums', '', '', true));
    $varnishCache->purgeUrl(COT_ABSOLUTE_URL);
} 