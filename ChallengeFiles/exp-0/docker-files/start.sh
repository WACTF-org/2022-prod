#!/bin/sh
# THIS RUNS AS ROOT

# fix sshd: no hostkeys available -- exiting.
/usr/bin/ssh-keygen -A

# run sshd
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf