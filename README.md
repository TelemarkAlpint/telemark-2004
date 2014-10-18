telemark-2004
=============

Websites from 2004-2005.

**Warning**: Repository includes images and videos from the dump, so it's rather big, ~500MB.

Extracted from `/home/groups/telemark/bob` on 16.10.2014. See git history to see what has been done to the stuff since then.

To run the PHP, fire up a VM, install apache and php, enable the `rewrite` mod, set `short_open_tag = On` in `/etc/php5/apache2/php.ini`, and symlink `/var/www` to `/vagrant` (assuming you use vagrant). Uncomment the `AddDefaultCharset utf-8` line in `conf-enabled/charset.conf`. Set `AllowOverride All` both in `apache.conf` and the vhost config, just to be sure. Restart apache.

To store a static, working copy of the site, ssh into the vm and execute

	$ wget -mpckE -e robots=off old.ntnuita.no && cd old.ntnuita.no && tar czf ../telemark-2004.tar.gz *

(assuming you've mapped old.ntnuita.no to 10.10.10.44)

Put the resulting file in `/home/groupswww/telemark/arkiv/websites/` so that the new webserver can find it and deploy it.
