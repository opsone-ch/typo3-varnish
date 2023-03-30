/*
 * Varnish Configuration for TYPO3
 *  !slighthly modified to use xtags
 *
 *	xkey is a module for varnish and must be installed first
 *
 * (c) 2013 Ops One AG <team@opsone.ch>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 */

vcl 4.1;
import xkey;

/*
 * this places acl and backend in seperate files for ease of use, 
 * you can include them directly as in 'default.vcl' instead
 */
include "/etc/varnish/includes/acls.vcl";
include "/etc/varnish/includes/backends.vcl";

/*
 * vcl_recv
 */


sub vcl_recv {
    call selectBackend;


	# Catch BAN Command
	if (req.method == "BAN" && client.ip ~ canPurge) {

		if (req.http.xkey-purge) {
			set req.http.n-gone = xkey.softpurge(req.http.xkey-purge);
			return (synth(200, "Invalidated "+req.http.n-gone+" objects"));
		}


		if(req.http.Varnish-Ban-All == "1" && req.http.Varnish-Ban-TYPO3-Sitename) {
			set req.http.n-gone = xkey.softpurge("siteId_"+req.http.Varnish-Ban-TYPO3-Sitename);
			return (synth(200, "Invalidated "+req.http.n-gone+" objects"));
		} else if(req.http.Varnish-Ban-All == "1") {
			#Do we ever want to do this?
			#ban("req.url ~ /");
			return(synth(200,"Can not invalidate all without sitename!"));
		}

		if(req.http.Varnish-Ban-TYPO3-Pid && req.http.Varnish-Ban-TYPO3-Sitename) {
			set req.http.n-gone = xkey.softpurge("pageId_"+req.http.Varnish-Ban-TYPO3-Pid);
			return (synth(200, "Invalidated "+req.http.n-gone+" objects"));			
		} else if(req.http.Varnish-Ban-TYPO3-Pid) {
			#Do we ever want to do this?
			return(synth(200,"Can not invalidate pid without sitename!"));					
		}

	}

	# Set X-Forwarded-For Header
	if (req.restarts == 0) {

		if (req.http.x-forwarded-for) {
			set req.http.X-Forwarded-For =
			req.http.X-Forwarded-For + ", " + client.ip;
		} else {
			set req.http.X-Forwarded-For = client.ip;
		}

	}

	# Pipe unknown Methods
	if (req.method != "GET" &&
		req.method != "HEAD" &&
		req.method != "PUT" &&
		req.method != "POST" &&
		req.method != "TRACE" &&
		req.method != "OPTIONS" &&
		req.method != "DELETE") {
		return (pipe);
	}

	# Cache only GET or HEAD Requests
	if (req.method != "GET" && req.method != "HEAD") {
		return (pass);
	}

	# do not cache TYPO3 BE User requests
	if (req.http.Cookie ~ "be_typo_user" || req.url ~ "^/typo3/") {
		return (pass);
	}

	# do not cache Authorized content
	if (req.http.Authorization) {
		return (pass);
	}

    # drop cookies and params from static assets    
    if (req.url ~ "\.(gif|jpg|jpeg|webp|swf|ttf|css|js|flv|mp3|mp4|pdf|ico|png|avif|eot|woff|woff2|svg)(\?.*|)$") {
        unset req.http.cookie;
        set req.url = regsub(req.url, "\?.*$", "");
    }


	/*
     * Typo3 sends fe cookies for every page regardless of weather it's needed
     * so once a user is logged in the would bypass all cache.
     * filter urls where the login cookie is needed here so we can cache everything else
     */
	if ( req.url ~ "^/ajax/" || req.url ~ "^/login/"){
		return (pass);
	}
	unset req.http.cookie;	

	# otherwise comment the above and uncomment below for the 'old' behaviour from default.vcl
	# if (req.http.Cookie) {
	# 	return (pass);
	# }

    /*
     * get rid of marketing crap before caching as it's used clientside only
     */
    if (req.url ~ "(\?|&)(utm_source|utm_medium|utm_campaign|utm_content|gclid|cx|ie|cof|siteurl)=") {
        set req.url = regsuball(req.url, "&(utm_source|utm_medium|utm_campaign|utm_content|gclid|cx|ie|cof|siteurl)=([A-z0-9_\-\.%25]+)", "");
        set req.url = regsuball(req.url, "\?(utm_source|utm_medium|utm_campaign|utm_content|gclid|cx|ie|cof|siteurl)=([A-z0-9_\-\.%25]+)", "?");
        set req.url = regsub(req.url, "\?&", "?");
        set req.url = regsub(req.url, "\?$", "");
    }


	# Lookup everything else in the Cache
	return (hash);
}


/*
 * vcl_backend_response
 */

sub vcl_backend_response {
    /*
     * setting a grace period means we are okay with handing out
     * invalidated content while varnish fetches new from typo3
     * comment the line if it doesn't fit your use case
     */
	set beresp.grace = 1h;
	# Cache only GET or HEAD Requests
	if (bereq.method != "GET" && bereq.method != "HEAD") {
		return (pass);
	}

	# Cache static Pages
	if (beresp.http.TYPO3-Pid && beresp.http.Pragma == "public") {
		unset beresp.http.Set-Cookie;
		return (deliver);
	}

	# do not cache everything else
	return (pass);
}


/*
 * vcl_deliver
 */

sub vcl_deliver {
	# Expires Header set by TYPO3 are used to define Varnish caching only
	# therefore do not send them to the Client
	if (resp.http.TYPO3-Pid && resp.http.Pragma == "public") {
		unset resp.http.expires;
		unset resp.http.pragma;
		unset resp.http.cache-control;
	}

	# smart Ban related
	unset resp.http.TYPO3-Pid;
	unset resp.http.TYPO3-Sitename;
	unset resp.http.xkey;


	return (deliver);
}