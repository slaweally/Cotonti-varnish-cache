# Varnish Cache Plugin for Cotonti

This plugin integrates Cotonti with Varnish Cache, a powerful HTTP accelerator that can significantly speed up your website.

## Features

- Seamless integration with Cotonti's existing cache system
- Admin panel for cache management
- Automatic cache purging when content is updated
- Configurable cache exclusions for specific URLs, admin pages, and logged-in users
- Debug mode for troubleshooting

## Requirements

- Cotonti Siena 0.9.x or higher
- Varnish Cache 4.0 or higher installed on your server
- PHP cURL extension

## Installation

1. Copy the `varnish` folder to your Cotonti `plugins` directory
2. Install the plugin via the Administration panel
3. The plugin works out of the box with default settings for most configurations

## Configuration

After installation, go to Administration → Extensions → Varnish Cache to configure the plugin:

- **Enabled**: Enable or disable Varnish integration (enabled by default)
- **Host**: Varnish host (default: 127.0.0.1)
- **Port**: Varnish admin port (default: 6082)
- **Secret**: Varnish secret (leave empty if not set)
- **TTL**: Default TTL for cached pages in seconds (default: 3600)
- **Exclude URLs**: URLs to exclude from caching (one per line, supports wildcards)
- **Exclude admin pages**: Exclude admin pages from caching (enabled by default)
- **Exclude logged-in users**: Exclude logged-in users from caching (enabled by default)
- **Purge on update**: Purge cache when content is updated (enabled by default)
- **Debug mode**: Log purge requests for debugging (disabled by default)

## Varnish Configuration

A sample VCL configuration file is included in the plugin directory (`vcl-example.txt`). Copy this file to your Varnish server and adjust it according to your needs.

Basic steps to set up Varnish:

1. Install Varnish Cache on your server
2. Copy and customize the VCL configuration
3. Configure your web server to work with Varnish
4. Restart Varnish and your web server

## Usage

Once configured, the plugin will automatically:

1. Set appropriate cache headers for pages
2. Exclude admin pages and logged-in users from caching (if configured)
3. Purge relevant cache entries when content is updated

You can manually purge specific URLs or the entire cache from the admin panel.

## API Functions

The plugin provides the following functions for developers:

- `cot_varnish_purge_url($url)`: Purge a specific URL from the cache
- `cot_varnish_purge_all()`: Purge the entire cache
- `cot_varnish_should_exclude()`: Check if the current URL should be excluded from caching
- `cot_varnish_set_headers()`: Set cache headers for the current response

## Author

Ali Çömez / Rootali

## License

BSD License 