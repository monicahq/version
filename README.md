<p align="center"><img src="https://app.monicahq.com/img/small-logo.png"></p>
<h1 align="center">Monica version checking</h1>

## Introduction

If you install Monica on a server that you own, chances are that you will want
to be aware when a new version is available. This is what this tool does.
In short:

* it does wait for a daily ping from Monica's instances
* the ping contains a UUID, the current version and the number of contacts in
the instance
* in return, the tool sends
    * if a new version is available as a boolean
    * what is the latest version available
    * all the release notes for each release that has been made since the version
    of the ping
    * the number of versions available since the version of the ping.

This information will be displayed to the instance owner inside Monica.

### Patreon

You can support the development of Monica
[on Patreon](https://www.patreon.com/monicahq). Thanks for your help.

## Contact

If you need to talk, you can contact me at regis AT monicahq DOT com. You can
also reach me [on Twitter](https://twitter.com/djaiss).

## License

Copyright (c) 2016-2017 Regis Freyd

Licensed under the AGPL License
