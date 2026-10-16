<?php
/**
 * Varnish Cache Plugin Page Add Done Hook
 *
 * @package Varnish
 * @copyright (c) Ali Çömez / Rootali
 * @license BSD
 */

/* ====================
[BEGIN_COT_EXT]
Hooks=page.add.add.done
Order=10
Tags=
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

// Load Varnish class
require_once cot_incfile('varnish', 'plug');

// Check if purge on update is enabled
if (Cot::$cfg['plugin']['varnish']['purge_on_update']) {
    // Purge home page and category page
    $varnishCache = VarnishCache::getInstance();
    $varnishCache->purgeUrl(COT_ABSOLUTE_URL);
    $varnishCache->purgeUrl(cot_url('page', 'c=' . $rpage['page_cat'], '', true));
} 