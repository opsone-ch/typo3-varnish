.. include:: ../Includes.txt

.. _information:

====================
Further Informations
====================

Compatibility
=============

- version 1 is compatible with TYPO3 versions 4.5 and 6.2
- version 2 is compatible with TYPO3 versions 7.6 and 8.7
- version 3 is compatible with TYPO3 versions 8.7 and 9.5
- version 4 is compatible with TYPO3 versions 9.5 and 10.4
- version 5 is compatible with TYPO3 versions 10.4 and 11.5
- version 6 is compatible with TYPO3 versions 11.5 and 12.4
- version 7 is compatible with TYPO3 versions 12.4 and 13.5

Best Practices
==============

- do not use sessions
- do not set no_cache=1
- do not use _INT objects
- do not use server-side switches based on clients/browsers properties/settings, e.g. user-agent
  or IP address

It is generally wise to avoid those objects by adapting the
extension or replace really required dynamic functions with
AJAX calls.

Support
=======

Please post questions to TYPO3 Slack (https://typo3.slack.com) or the Forum (https://talk.typo3.org).

Commercial support and further consulting is available from Ops One AG.
Feel free to contact us at team@opsone.ch if you need commercial support.

Development
===========

This extension is managed on GitLab. Feel free to get in touch at
https://gitlab.com/opsone_ch/typo3/varnish/.

