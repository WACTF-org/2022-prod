FROM alpine:latest

# allows to not cache the index locally, keeps containers small
# https://gist.github.com/sgreben/dfeaaf20eb635d31e1151cb14ea79048
RUN apk add --no-cache

# things players can have
RUN apk add openssh supervisor \
			bash curl wget netcat-openbsd vim nano tmux iputils socat \
			openjdk8 py3-pip python3 && ln -sf python3 /usr/bin/python

RUN python3 -m pip install colorama argparse

# create a new user and group
RUN adduser -S -s /bin/bash -D user
RUN echo "user:toor" | chpasswd

# move supervisor config and lock to root
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod 400 /etc/supervisor/conf.d/supervisord.conf

# move start script and lock to root
COPY start.sh /root/start.sh
RUN chmod 700 /root/start.sh

# v. important
COPY motd.txt /etc/motd
# less important
COPY readme.txt /home/user/readme.txt

# install sqlmap
RUN mkdir /home/user/sqlmap
RUN wget -q -O - https://github.com/sqlmapproject/sqlmap/tarball/master | tar -xz --strip-components=1 -C /home/user/sqlmap -f -
RUN chown -R user:nogroup /home/user/sqlmap/

# default command to run when container starts
CMD ["/root/start.sh"]