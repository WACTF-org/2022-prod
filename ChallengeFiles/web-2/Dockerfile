FROM alpine:latest
# Add a low priv user.
RUN adduser -S -s /bin/false -H -D user

# Copy stuff
COPY ./server /server

# Drop privs
USER user
WORKDIR /server
ENTRYPOINT [ "/server/app" ]