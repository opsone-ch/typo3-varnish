.. include:: ../Includes.txt

.. _introduction:

============
Introduction
============

.. _what-it-does:

What does it do?
================

This extension tells Varnish about TYPO3 insights of a page to allow Varnish make proper caching decisions based on those information.
Furthermore, it informs Varnish to invalidate its cache as soon as content is changed through the TYPO3 backend.

.. _features:

Features
========

- ready to use configration for Varnish and TYPO3
- Varnish based caching for all cacheable pages
- TYPO3 clear cache hook to clear cache for appropriate pages in Varnish too

Why another Varnish Extension?
==============================

While there are already a few extensions for Varnish and TYPO3 available, we decided to build our own for the following reasons:

- use the full power of current Varnish version (especially smart BAN)
- use TYPO3 core functions and configuration directives whenever possible
- do not collect all feasible URLs for a given TYPO3 page to send appropriate PURGE commands to Varnish but send Varnish the current TYPO3 page ID only.
  This makes the whole interaction between the systems more robust.
- do not use Varnish Edge Side Includes (ESI) in combination with some "magic" TYPO3 FE processing
- in short: This powerful extension follows the KISS principle

This took place back in 2013. Meanwhile, this extension evolved to the defacto standard for using Varnish with TYPO3.

Technical Background
====================

- the extension sets config.sendCacheHeaders = 1 to enable TYPO3 core function which sends appropriate cache headers to Varnish
- it sends "TYPO3-Pid" HTTP Header which is used to issue BAN command against
- it sends appropriate BAN Command to Varnish during a TYPO3 clearCache action
- it extends TYPO3 backend cacheActions to allow manual Varnish cache clearing
- those headers are used for Varnish processing only and get removed afterwards
