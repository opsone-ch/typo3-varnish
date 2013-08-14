
.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: Includes.txt

.. _start:

=============================================================
Varnish
=============================================================

.. raw:: latex

	\begin{sphinxproperties}
	\relax

:Classification:
	varnish

:Version:
	|release|

:Language:
	en

:Description:
	Varnish is the fastest, most flexible and cost efficient web accelerator available for your online business. It speeds up your website and boosts user loyalty and customer conversion rate and helps to save hosting costs.

:Copyright:
	snowflake productions gmbh, 2013

:Author:
	Andri Steiner, snowflake productions gmbh

:Email:
	varnish@snowflake.ch

:License:
	This document is published under the Open Content License
	available from http://www.opencontent.org/opl.shtml

:Rendered:
	|today|

.. raw:: latex

	\vfill

The content of this document is related to TYPO3

\- a GNU/GPL CMS/Framework available from `www.typo3.org <http://www.typo3.org/>`_

.. raw:: latex

	\end{sphinxproperties}

Introduction
-------------------------------------------------------------


What does it do?
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This extension tells Varnish about TYPO3 insights of a page to allow Varnish
to make proper caching decisions based on those information. Furthermore,
it informs Varnish to invalidate its cache as soon as content is changed through
the TYPO3 backend.


Features
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- Ready to use configration for Varnish and TYPO3 
- Varnish based caching for all cacheable pages
- TYPO3 clear cache hook to clear cache for approrpatie pages in Varnish too


Why another Varnish Extension?
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

While there are already a few extensions for Varnish and TYPO3 available, we
decided to build our own for the following reasons:


- Use the full power of current Varnish version (especially smart BAN)
- Use TYPO3 core functions and configuration directives whenever possible
- Do not collect all feasible URLs for a given TYPO3 page to send appropriate
  PURGE commands to Varnish but send Varnish the current TYPO3 page ID only. This makes the whole interaction between the systems more robust.
- Do not use Varnish Edge Side Includes (ESI) in combination with some "magic"
  TYPO3 FE processing
- In short: This powerful extension follows the KISS principle


Technical Background
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- The extension sets config.sendCacheHeaders = 1 to enable TYPO3 core function which sends
  appropriate cache headers to Varnish
- It sends "TYPO3-Pid" HTTP Header which is used to issue BAN command against
- It sends appropriate BAN Command to Varnish during a TYPO3 clearCache action
- It extends TYPO3 backend cacheActions to allow manual Varnish cache clearing
- Those headers are used for Varnish processing only and get removed afterwards


Installation
-------------------------------------------------------------


Requirements
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- A test environment might be a good idea!
- You should make yourself familiar with Varnish and how you want to
  implement Varnish in your specific setup. A good starting point is the
  great Varnish book available at https://www.varnish-software.com/book/.
- Varnish has to be up and running. You can find a sample configuration in 
  the extension's res folder.
- Requests to all static files should send appropriate expires headers



Extension Installation/Configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- Set :php:`$TYPO3_CONF_VARS['SYS']['reverseProxyIP']` to the IP address
  which is used by Varnish to connect to your Webserver
- Install this extension
- By default, TYPO3 sends BAN commands to Varnish to the hostname which is used
  by the current backend session. If your Varnish server listens at another
  address you can change this through the extension configuration.


Debugging
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- Use Varnish debbuging tools, e.g. varnishlog
- Enable debug mode by the extension configuration (requires 3rd party
  TYPO3 developer log extension, e.g. "devlog")



Further Information
-------------------------------------------------------------


Best Practices
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- Do not use sessions
- Do not set no_cache=1 
- Do not use _INT objects
- Do not use server-side switches based on clients/browsers properties/settings, e.g. user-agent
  or IP address

It is generally wise to avoid those objects by adapting the
extension or replace really required dynamic functions with
AJAX calls.


Support
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Please send all your questions to the `TYPO3-English <http://lists.typo3.org/cgi-bin/mailman/listinfo/typo3-english>`_ Mailinglist.

Commercial support and further consulting is available from snowflake. snowflake
is an official Varnish integration and hosting partner. Feel free to contact us at 
varnish@snowflake.ch if you need commercial support.


Development
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This extension is managed on GitHub. Feel free to get in touch at
https://github.com/snowflakech/typo3-varnish/.