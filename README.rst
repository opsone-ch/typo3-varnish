.. _start:

=============================================================
Varnish
=============================================================

:Classification:
	varnish

:Version:
	|release|

:Language:
	en

:Description:
	Varnish is the fastest, most flexible and cost efficient web accelerator available for your online business. It speeds up your website and boosts user loyalty and customer conversion rate and helps to save hosting costs.

:Copyright:
	Ops One AG, 2013 - 2020

:Author:
	Andri Steiner, Ops One AG

:Email:
	team@opsone.ch

:License:
	This document is published under the Open Content License
	available from http://www.opencontent.org/opl.shtml

:Rendered:
	|today|

The content of this document is related to TYPO3

\- a GNU/GPL CMS/Framework available from `www.typo3.org <http://www.typo3.org/>`_

Introduction
-------------------------------------------------------------


What does it do?
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This extension tells Varnish about TYPO3 insights of a page to allow Varnish
make proper caching decisions based on those information. Furthermore, it
informs Varnish to invalidate its cache as soon as content is changed through
the TYPO3 backend.


Features
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- ready to use configration for Varnish and TYPO3
- Varnish based caching for all cacheable pages
- TYPO3 clear cache hook to clear cache for appropriate pages in Varnish too


Why another Varnish Extension?
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

While there are already a few extensions for Varnish and TYPO3 available, we
decided to build our own for the following reasons:


- use the full power of current Varnish version (especially smart BAN)
- use TYPO3 core functions and configuration directives whenever possible
- do not collect all feasible URLs for a given TYPO3 page to send appropriate
  PURGE commands to Varnish but send Varnish the current TYPO3 page ID only. This makes the whole interaction between the systems more robust.
- do not use Varnish Edge Side Includes (ESI) in combination with some "magic"
  TYPO3 FE processing
- in short: This powerful extension follows the KISS principle


Technical Background
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- the extension sets config.sendCacheHeaders = 1 to enable TYPO3 core function which sends
  appropriate cache headers to Varnish
- it sends "TYPO3-Pid" HTTP Header which is used to issue BAN command against
- it sends appropriate BAN Command to Varnish during a TYPO3 clearCache action
- it extends TYPO3 backend cacheActions to allow manual Varnish cache clearing
- those headers are used for Varnish processing only and get removed afterwards


Installation
-------------------------------------------------------------


Requirements
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- a test environment might be a good idea!
- you should make yourself familiar with Varnish and how you want to
  implement Varnish in your specific setup. A good starting point is the
  great Varnish book available at https://info.varnish-software.com/the-varnish-book.
- Varnish has to be up and running. You can find a sample configuration in the extension's res folder
- requests to all static files should send appropriate expires headers


Extension Installation/Configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- set :php:`$TYPO3_CONF_VARS['SYS']['reverseProxyIP']` to the IP address
  which is used by Varnish to connect to your Webserver
- Install this extension
- by default, TYPO3 sends BAN commands to Varnish to the hostname which is used
  by the current backend session. If your Varnish server listens at another
  address you can change this through the extension configuration.


Debugging
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- use Varnish debbuging tools, e.g. varnishlog
- enable debug mode by the extension configuration (requires 3rd party
  TYPO3 developer log extension, e.g. "devlog")



Further Information
-------------------------------------------------------------


Compatibility
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- version 1 is compatible with TYPO3 versions 4.5 and 6.2
- version 2 is compatible with TYPO3 versions 7.6 and 8.7
- version 3 is compatible with TYPO3 versions 8.7 and 9.5
- version 4 is compatible with TYPO3 versions 9.5 and 10.4


Best Practices
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- do not use sessions
- do not set no_cache=1
- do not use _INT objects
- do not use server-side switches based on clients/browsers properties/settings, e.g. user-agent
  or IP address

It is generally wise to avoid those objects by adapting the
extension or replace really required dynamic functions with
AJAX calls.


Support
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Please post questions to TYPO3 Slack (https://typo3.slack.com) or the Forum (https://talk.typo3.org).

Commercial support and further consulting is available from Ops One AG.
Feel free to contact us at team@opsone.ch if you need commercial support.


Development
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This extension is managed on GitHub. Feel free to get in touch at
https://gitlab.com/opsone_ch/typo3/varnish/.

