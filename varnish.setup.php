<?php
/* ====================
[BEGIN_COT_EXT]
Code=varnish
Name=Varnish Cache
Category=performance-seo
Description=Varnish Cache integration for Cotonti
Version=1.0.0
Date=2025-06-23
Author=Ali Çömez / Rootali
Copyright=Copyright (c) Ali Çömez / Rootali 2025
Notes=BSD License
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
Requires_modules=
Requires_plugins=
Recommends_modules=
Recommends_plugins=
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
enabled=01:radio::1:Enable Varnish integration
host=02:string:127.0.0.1:Varnish host
port=03:string:80:Varnish HTTP port (usually 80)
admin_port=04:string:6082:Varnish admin port (usually 6082)
server_port=05:string:6081:Varnish server port (usually 6081)
secret=06:string::Varnish secret (leave empty if not set)
ttl=07:select:60,300,600,1800,3600,7200,86400,604800:604800:Default TTL for cached pages (seconds)
cache_tag_prefix=08:string:cot_:Cache tag prefix
excluded_params=09:string:__SID,noCache:Excluded GET parameters (comma separated)
exclude_urls=10:textarea:admin.php\nlogin.php\nregister.php\npassword.php\nprofile.php\nusers.php\n^/admin/:URLs to exclude from caching (one per line, supports wildcards and regex with ^ prefix)
exclude_admin=11:radio::1:Exclude admin pages from caching
exclude_users=12:radio::1:Exclude logged-in users from caching
purge_on_update=13:radio::1:Purge cache when content is updated
debug_mode=14:radio::0:Debug mode (log purge requests)
[END_COT_EXT_CONFIG]
==================== */

/**
 * Varnish Cache setup file
 *
 * @package Varnish
 * @copyright (c) Ali Çömez / Rootali
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL'); 