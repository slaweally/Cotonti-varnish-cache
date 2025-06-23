<!-- BEGIN: MAIN -->
<h2>{PHP.L.varnish_name}</h2>

<div class="block">
    <h3>{PHP.L.varnish_status}</h3>
    
    <table class="cells">
        <tr>
            <td class="coltop" colspan="2">{PHP.L.varnish_status}</td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_enabled}</td>
            <td><!-- IF {VARNISH_ENABLED} -->{PHP.L.Yes}<!-- ELSE -->{PHP.L.No}<!-- ENDIF --></td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_reachable}</td>
            <td>
                <!-- IF {VARNISH_REACHABLE} -->
                <span class="text-success">{PHP.L.Yes}</span>
                <!-- ELSE -->
                <span class="text-danger">{PHP.L.No}</span>
                <!-- ENDIF -->
            </td>
        </tr>
        <!-- IF {VARNISH_CLOUDPANEL} -->
        <tr>
            <td>{PHP.L.varnish_cloudpanel_detected}</td>
            <td><span class="text-success">{PHP.L.Yes}</span></td>
        </tr>
        <!-- ENDIF -->
        <tr>
            <td>{PHP.L.varnish_host}</td>
            <td>{VARNISH_HOST}</td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_port}</td>
            <td>{VARNISH_PORT}</td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_admin_port}</td>
            <td>{VARNISH_ADMIN_PORT}</td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_server_port}</td>
            <td>{VARNISH_SERVER_PORT}</td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_ttl}</td>
            <td>{VARNISH_TTL} {PHP.L.varnish_seconds}</td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_cache_tag_prefix}</td>
            <td>{VARNISH_CACHE_TAG_PREFIX}</td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_excluded_params}</td>
            <td>{VARNISH_EXCLUDED_PARAMS}</td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_exclude_admin}</td>
            <td><!-- IF {VARNISH_EXCLUDE_ADMIN} -->{PHP.L.Yes}<!-- ELSE -->{PHP.L.No}<!-- ENDIF --></td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_exclude_users}</td>
            <td><!-- IF {VARNISH_EXCLUDE_USERS} -->{PHP.L.Yes}<!-- ELSE -->{PHP.L.No}<!-- ENDIF --></td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_purge_on_update}</td>
            <td><!-- IF {VARNISH_PURGE_ON_UPDATE} -->{PHP.L.Yes}<!-- ELSE -->{PHP.L.No}<!-- ENDIF --></td>
        </tr>
        <tr>
            <td>{PHP.L.varnish_debug_mode}</td>
            <td><!-- IF {VARNISH_DEBUG_MODE} -->{PHP.L.Yes}<!-- ELSE -->{PHP.L.No}<!-- ENDIF --></td>
        </tr>
    </table>
    
    <p><a href="{VARNISH_CONFIG_URL}" class="button">{PHP.L.varnish_edit_config}</a></p>
</div>

<div class="block">
    <h3>{PHP.L.varnish_purge_url}</h3>
    
    <form action="{VARNISH_PURGE_URL_FORM_ACTION}" method="get">
        <input type="hidden" name="m" value="other" />
        <input type="hidden" name="p" value="varnish" />
        <input type="hidden" name="action" value="purge" />
        <input type="hidden" name="csrf_token" value="{VARNISH_CSRF_TOKEN}" />
        
        <div class="form-group">
            <label for="url">{PHP.L.varnish_url}:</label>
            <input type="text" name="url" id="url" class="form-control" placeholder="https://example.com/page" required />
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">{PHP.L.varnish_purge}</button>
        </div>
    </form>
</div>

<div class="block">
    <h3>{PHP.L.varnish_purge_tag}</h3>
    
    <form action="{VARNISH_PURGE_TAG_FORM_ACTION}" method="get">
        <input type="hidden" name="m" value="other" />
        <input type="hidden" name="p" value="varnish" />
        <input type="hidden" name="action" value="purge_tag" />
        <input type="hidden" name="csrf_token" value="{VARNISH_CSRF_TOKEN}" />
        
        <div class="form-group">
            <label for="tag">{PHP.L.varnish_tag}:</label>
            <input type="text" name="tag" id="tag" class="form-control" placeholder="page_123" required />
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">{PHP.L.varnish_purge_tag_button}</button>
        </div>
    </form>
    
    <p>{PHP.L.varnish_purge_tag_help}</p>
    
    <h4>{PHP.L.varnish_common_tags}</h4>
    <ul>
        <li><strong>all</strong> - {PHP.L.varnish_tag_all}</li>
        <li><strong>page</strong> - {PHP.L.varnish_tag_page}</li>
        <li><strong>forums</strong> - {PHP.L.varnish_tag_forums}</li>
        <li><strong>page_123</strong> - {PHP.L.varnish_tag_page_id}</li>
        <li><strong>forums_section_5</strong> - {PHP.L.varnish_tag_forums_section}</li>
        <li><strong>forums_topic_42</strong> - {PHP.L.varnish_tag_forums_topic}</li>
    </ul>
</div>

<div class="block">
    <h3>{PHP.L.varnish_purge_all}</h3>
    
    <p>{PHP.L.varnish_purge_all_help}</p>
    
    <p>
        <a href="{VARNISH_PURGE_ALL_URL}&csrf_token={VARNISH_CSRF_TOKEN}" class="btn btn-danger" onclick="return confirm('{PHP.L.varnish_purge_all_confirm}')">{PHP.L.varnish_purge_all}</a>
    </p>
</div>

<div class="block">
    <h3>{PHP.L.varnish_help_title}</h3>
    
    <p>{PHP.L.varnish_help_text}</p>
    
    <h4>{PHP.L.varnish_vcl_title}</h4>
    
    <p>{PHP.L.varnish_vcl_text}</p>
    
    <h4>{PHP.L.varnish_cloudpanel_title}</h4>
    
    <p>{PHP.L.varnish_cloudpanel_text}</p>
</div>
<!-- END: MAIN --> 
