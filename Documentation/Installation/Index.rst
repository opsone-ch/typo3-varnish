.. include:: ../Includes.txt

.. _installation:

============
Installation
============

Requirements
============

- setup a dedicated test environment
- make yourself familiar with Varnish and how you want to
  implement Varnish in your specific setup. A good starting point is the
  great Varnish book available at https://info.varnish-software.com/the-varnish-book
- Varnish has to be up and running. There is a sample configuration in Resources/Private/Example/default.vcl
- requests to all static files should send appropriate expires headers

Configuration
=============

- set :ref:`t3coreapi:typo3ConfVars_sys_reverseProxyIP`
  to the IP address which is used by Varnish to connect to your Webserver
- Install this extension
- by default, TYPO3 sends BAN commands to Varnish to the hostname which is used
  by the current backend session. If your Varnish server listens at another
  address you can change this through the extension configuration.

Debugging
=========

- use Varnish debbuging tools, e.g. varnishlog
- enable debug mode by the extension configuration (requires 3rd party
  TYPO3 developer log extension, e.g. "devlog")

