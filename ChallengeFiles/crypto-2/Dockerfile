FROM alpine:latest

# add bash to manage process restarts
RUN apk update --no-cache && apk add bash
# Add a low priv user.
RUN adduser -S -s /bin/false -H -D user

# Copy stuff
COPY ./server /server

# Drop privs
USER user
ENTRYPOINT [ "/server/runme.sh" ]