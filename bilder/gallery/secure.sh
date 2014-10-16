#!/bin/sh
# $Id: secure.sh,v 1.9 2004/02/24 18:38:13 jenst Exp $

chmod 0 setup

if [ -f config.php ]; then
    chmod 644 config.php 
fi

if [ -f .htaccess ]; then
    chmod 644 .htaccess
fi

echo ""
echo "Your Gallery is now secure and cannot be configured.  If"
echo "you wish to reconfigure it, run:"
echo ""
echo "    % sh configure.sh"
echo ""
