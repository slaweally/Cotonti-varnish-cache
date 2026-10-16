<?php
/**
 * Varnish Cache Plugin
 *
 * @package Varnish
 * @copyright (c) Ali Çömez / Rootali
 * @license BSD
 */

/* ====================
[BEGIN_COT_EXT]
Hooks=global
Order=10
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

// Define constants
define('VARNISH_DEBUG', (bool)Cot::$cfg['plugin']['varnish']['debug_mode']);

/**
 * Varnish Cache class
 */
class VarnishCache
{
    /**
     * Singleton instance
     * @var VarnishCache
     */
    protected static $instance;
    
    /**
     * Varnish host
     * @var string
     */
    protected $host;
    
    /**
     * Varnish HTTP port
     * @var int
     */
    protected $port;
    
    /**
     * Varnish admin port
     * @var int
     */
    protected $adminPort;
    
    /**
     * Varnish server port
     * @var int
     */
    protected $serverPort;
    
    /**
     * Varnish secret
     * @var string
     */
    protected $secret;
    
    /**
     * Default TTL
     * @var int
     */
    protected $ttl;
    
    /**
     * Cache tag prefix
     * @var string
     */
    protected $cacheTagPrefix;
    
    /**
     * Excluded GET parameters
     * @var array
     */
    protected $excludedParams;
    
    /**
     * Excluded URLs
     * @var array
     */
    protected $excludeUrls;
    
    /**
     * Is plugin enabled
     * @var bool
     */
    protected $enabled;
    
    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->host = Cot::$cfg['plugin']['varnish']['host'];
        $this->port = (int)Cot::$cfg['plugin']['varnish']['port'];
        $this->adminPort = (int)Cot::$cfg['plugin']['varnish']['admin_port'];
        $this->serverPort = (int)Cot::$cfg['plugin']['varnish']['server_port'];
        $this->secret = Cot::$cfg['plugin']['varnish']['secret'];
        $this->ttl = (int)Cot::$cfg['plugin']['varnish']['ttl'];
        $this->cacheTagPrefix = Cot::$cfg['plugin']['varnish']['cache_tag_prefix'];
        $this->excludedParams = explode(',', Cot::$cfg['plugin']['varnish']['excluded_params']);
        $this->excludeUrls = explode("\n", Cot::$cfg['plugin']['varnish']['exclude_urls']);
        $this->enabled = (bool)Cot::$cfg['plugin']['varnish']['enabled'];
    }
    
    /**
     * Get singleton instance
     * @return VarnishCache
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Check if plugin is enabled
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    /**
     * Check if current URL should be excluded from caching
     * @return bool
     */
    public function shouldExclude()
    {
        // If plugin is disabled, exclude from caching
        if (!$this->enabled) {
            return true;
        }
        
        // Exclude admin pages if configured
        if (Cot::$cfg['plugin']['varnish']['exclude_admin'] && defined('COT_ADMIN')) {
            return true;
        }
        
        // Exclude logged-in users if configured
        if (Cot::$cfg['plugin']['varnish']['exclude_users'] && Cot::$usr['id'] > 0) {
            return true;
        }
        
        // Check for excluded GET parameters
        foreach ($this->excludedParams as $param) {
            $param = trim($param);
            if (empty($param)) {
                continue;
            }
            
            if (isset($_GET[$param])) {
                return true;
            }
        }
        
        // Check excluded URLs
        $currentUrl = $_SERVER['REQUEST_URI'];
        foreach ($this->excludeUrls as $pattern) {
            $pattern = trim($pattern);
            if (empty($pattern)) {
                continue;
            }
            
            // Check if it's a regex pattern (starts with ^)
            if (substr($pattern, 0, 1) === '^') {
                if (preg_match('/' . $pattern . '/i', $currentUrl)) {
                    return true;
                }
            } else {
                // Convert wildcard pattern to regex
                $regex = str_replace(
                    array('\*', '\?', '/', '.'),
                    array('.*', '.', '\/', '\.'),
                    preg_quote($pattern, '/')
                );
                
                if (preg_match('/^' . $regex . '$/i', $currentUrl)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Set cache headers for the current response
     */
    public function setCacheHeaders()
    {
        if ($this->shouldExclude()) {
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Add custom header to tell Varnish not to cache this
            header('X-Varnish-Cache: BYPASS');
        } else {
            // Set cache headers
            header('Cache-Control: public, max-age=' . $this->ttl);
            header('X-Varnish-Cache: HIT');
            
            // Add cache tags if prefix is set
            if (!empty($this->cacheTagPrefix)) {
                $tags = array();
                
                // Add default tags
                $tags[] = $this->cacheTagPrefix . 'all';
                
                // Add module-specific tags
                if (defined('COT_MODULE') && !empty(Cot::$env['ext'])) {
                    $tags[] = $this->cacheTagPrefix . Cot::$env['ext'];
                }
                
                // Add page-specific tags for page module
                if (defined('COT_MODULE') && Cot::$env['ext'] === 'page' && !empty(Cot::$env['item'])) {
                    $tags[] = $this->cacheTagPrefix . 'page_' . Cot::$env['item'];
                }
                
                // Add forum-specific tags for forums module
                if (defined('COT_MODULE') && Cot::$env['ext'] === 'forums') {
                    if (!empty(Cot::$env['section'])) {
                        $tags[] = $this->cacheTagPrefix . 'forums_section_' . Cot::$env['section'];
                    }
                    if (!empty(Cot::$env['topic'])) {
                        $tags[] = $this->cacheTagPrefix . 'forums_topic_' . Cot::$env['topic'];
                    }
                }
                
                // Set the cache tags header
                if (!empty($tags)) {
                    header('X-Cache-Tags: ' . implode(',', $tags));
                }
            }
        }
    }
    
    /**
     * Purge URL from Varnish cache
     * @param string $url URL to purge
     * @return bool Success
     */
    public function purgeUrl($url)
    {
        if (!$this->enabled) {
            return false;
        }
        
        $parsedUrl = parse_url($url);
        $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : $_SERVER['HTTP_HOST'];
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '/';
        
        if (isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
            $path .= '?' . $parsedUrl['query'];
        }
        
        return $this->sendPurge($domain, $path);
    }
    
    /**
     * Purge all cache
     * @return bool Success
     */
    public function purgeAll()
    {
        if (!$this->enabled) {
            return false;
        }
        
        return $this->sendPurge('', '.*');
    }
    
    /**
     * Purge cache by tag
     * @param string $tag Cache tag
     * @return bool Success
     */
    public function purgeByTag($tag)
    {
        if (!$this->enabled || empty($this->cacheTagPrefix)) {
            return false;
        }
        
        $fullTag = $this->cacheTagPrefix . $tag;
        
        // Use BAN method for tag-based purging
        $ch = curl_init('http://' . $this->host . ':' . $this->adminPort);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'BAN');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Cache-Tags: ' . $fullTag,
            'X-Varnish-Ban-Tags: 1'
        ));
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $success = ($httpCode == 200);
        
        if (VARNISH_DEBUG) {
            cot_log("Varnish: Purge tag $fullTag result: " . ($success ? 'Success' : 'Failed') . " ($httpCode)", 'varnish');
        }
        
        return $success;
    }
    
    /**
     * Send PURGE request to Varnish
     * @param string $domain Domain
     * @param string $path Path
     * @return bool Success
     */
    protected function sendPurge($domain, $path)
    {
        if (empty($domain)) {
            $domain = $_SERVER['HTTP_HOST'];
        }
        
        $purgeUrl = 'http://' . $this->host . ':' . $this->adminPort . $path;
        
        if (VARNISH_DEBUG) {
            cot_log("Varnish: Purging URL: $purgeUrl", 'varnish');
        }
        
        $ch = curl_init($purgeUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PURGE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Host: ' . $domain,
            'X-Varnish-Purge: 1'
        ));
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $success = ($httpCode == 200);
        
        if (VARNISH_DEBUG) {
            cot_log("Varnish: Purge result: " . ($success ? 'Success' : 'Failed') . " ($httpCode)", 'varnish');
        }
        
        return $success;
    }
}

// Get the Varnish Cache instance
$varnishCache = VarnishCache::getInstance();

// Set cache headers for the current response
if (!defined('COT_AJAX')) {
    $varnishCache->setCacheHeaders();
} 