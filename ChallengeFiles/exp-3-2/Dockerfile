FROM ubuntu:20.04

RUN apt-get update && \
    apt-get install --no-install-recommends -y socat bash && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    du -sh /var/cache/apt && \
    apt-get autoclean && \
    apt-get clean

RUN useradd -m ctf
WORKDIR /home/ctf
COPY ./* /home/ctf/
RUN chown -R root:ctf * && \
    chmod -R 755 * && \
    chmod +x /home/ctf/pwnable && \
    chmod 755 /home/ctf/flag.txt

CMD ["/bin/bash", "/home/ctf/start.sh"]
EXPOSE 1337