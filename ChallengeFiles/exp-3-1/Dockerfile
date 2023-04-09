FROM alpine:latest

RUN apk add --no-cache socat coreutils libc6-compat bash && rm -rf /var/cache/apk/*
RUN addgroup ctf
RUN adduser -S -s /bin/false -D ctf -G ctf

COPY ./start.sh /start.sh
RUN chown root:ctf /start.sh && chmod +x /start.sh

WORKDIR /home/ctf
COPY ./bin/ .
RUN chmod +x pwnable

USER ctf
CMD ["/bin/bash", "/start.sh"]
EXPOSE 1337