* [ ] raise version in `ext_emconf.php`
* [ ] raise version in `Documentation/Settings.cfg`
* [ ] merge request to `main` branch
* [ ] tag version
```
git tag x.y.z
git push origin x.y.z
```
* [ ] create zip archive and upload to TYPO3 extension repository
```
zip -r ../my_extension_key_x.y.z.zip *
```
* [ ] check https://extensions.typo3.org/extension/varnish
* [ ] when required, adapt release template
* [ ] close [milestone](https://gitlab.com/opsone_ch/typo3/varnish/-/milestones)
* [ ] Tweet
