FROM golang:alpine AS builder

WORKDIR $GOPATH/src/mypackage/myapp/
COPY docker-files/server.go main.go
# Build the binary.
RUN go mod init
RUN go build -o /go/bin/server

FROM alpine:latest

# allows to not cache the index locally, keeps containers small
# https://gist.github.com/sgreben/dfeaaf20eb635d31e1151cb14ea79048
RUN apk add --no-cache

# need to have socat
RUN apk add socat

# create a new user and group
RUN adduser -S -s /bin/false -H -D myuser

# Copy our static executable.
COPY --from=builder /go/bin/server /home/myuser/server

# v. important
COPY docker-files/ascii.txt /home/myuser/ascii.txt
COPY docker-files/maybe_check_this_file.txt /home/myuser/maybe_check_this_file.txt

USER myuser
WORKDIR /home/myuser/

# default command to run when container starts
ENTRYPOINT ["socat", "-s", "TCP-LISTEN:1337,reuseaddr,fork", "EXEC:./server"]
