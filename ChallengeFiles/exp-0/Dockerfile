FROM alpine:latest

# allows to not cache the index locally, keeps containers small
# https://gist.github.com/sgreben/dfeaaf20eb635d31e1151cb14ea79048
RUN apk add --no-cache

# things players can have
RUN apk add openssh supervisor \
			bash vim nano

# create a new user and group
RUN adduser -S -s /bin/bash -D low_priv
RUN echo "low_priv:ou4h5ySDgek" | chpasswd

# create ssh folder
RUN mkdir -p /home/low_priv/.ssh && \
	chown low_priv:nogroup /home/low_priv/.ssh && \
    chmod 0700 /home/low_priv/.ssh

COPY --chown=low_priv:nogroup docker-files/id_rsa.pub /home/low_priv/.ssh/authorized_keys
RUN chmod 644 /home/low_priv/.ssh/authorized_keys

# change ssh port 22123
# we do this in docker-compose instead
#sed -i -e "/Port /c\Port 22123" /etc/ssh/sshd_config

# move supervisor config and lock to root
COPY docker-files/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod 400 /etc/supervisor/conf.d/supervisord.conf

# move start script and lock to root
COPY docker-files/start.sh /root/start.sh
RUN chmod 700 /root/start.sh

# v. important
COPY docker-files/motd.txt /etc/motd

# less important
COPY docker-files/flag.txt /home/low_priv/.the_flag_is_in_this_file
RUN chmod 444 /home/low_priv/.the_flag_is_in_this_file

# default command to run when container starts
CMD ["/root/start.sh"]