/*
 * snowflake Varnish Configuration for TYPO3
 * 
 * (c) 2013 snowflake productions gmbh <varnish@snowflake.ch>
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


/*
 * Declare the default Backend Server
 */

backend default {
	.host = "localhost";
	.port = "80";
}


/*
 * BAN ACL
 * Clients in this lists are allowed to issue BAN commands
 */

acl ban {
	"127.0.0.1";
}


/*
 * vcl_recv
 */

sub vcl_recv {
	# Catch BAN Command
	if (req.request == "BAN" && client.ip ~ ban) {

		if(req.http.Varnish-Ban-All == "1" && req.http.Varnish-Ban-TYPO3-Sitename) {
			ban("req.url ~ /" + " && obj.http.TYPO3-Sitename == " + req.http.Varnish-Ban-TYPO3-Sitename);
			error 200 "Banned all on site " + req.http.Varnish-Ban-TYPO3-Sitename;
		} else if(req.http.Varnish-Ban-All == "1") {
			ban("req.url ~ /");
			error 200 "Banned all";
		}

		if(req.http.Varnish-Ban-TYPO3-Pid && req.http.Varnish-Ban-TYPO3-Sitename) {
			ban("obj.http.TYPO3-Pid == " + req.http.Varnish-Ban-TYPO3-Pid + " && obj.http.TYPO3-Sitename == " + req.http.Varnish-Ban-TYPO3-Sitename);
			error 202 "Banned TYPO3 pid " + req.http.Varnish-Ban-TYPO3-Pid + " on site " + req.http.Varnish-Ban-TYPO3-Sitename;
		} else if(req.http.Varnish-Ban-TYPO3-Pid) {
			ban("obj.http.TYPO3-Pid == " + req.http.Varnish-Ban-TYPO3-Pid);
			error 200 "Banned TYPO3 pid " + req.http.Varnish-Ban-TYPO3-Pid;
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
	if (req.request != "GET" &&
		req.request != "HEAD" &&
		req.request != "PUT" &&
		req.request != "POST" &&
		req.request != "TRACE" &&
		req.request != "OPTIONS" &&
		req.request != "DELETE") {
		return (pipe);
	}

	# Cache only GET or HEAD Requests
	if (req.request != "GET" && req.request != "HEAD") {
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

	# do not cache Cookie based content
	if (req.http.Cookie) {
		return (pass);
	}

	# Lookup everything else in the Cache
	return (lookup);
}


/*
 * vcl_fetch
 */

sub vcl_fetch {
	# Cache only GET or HEAD Requests
	if (req.request != "GET" && req.request != "HEAD") {
		return (hit_for_pass);
	}

	# Cache static files
	if (req.url ~ "^[^?]*\.(css|js|htc|txt|swf|flv|pdf|gif|jpe?g|png|ico|woff|ttf|eot|otf|xml|md5|json)($|\?)") {
		return (deliver);
	}

	# Cache static Pages
	if (beresp.http.TYPO3-Pid && beresp.http.Pragma == "public") {
		unset beresp.http.Set-Cookie;
		return (deliver);
	}

	# do not cache everything else
	return (hit_for_pass);
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

	return (deliver);
}

