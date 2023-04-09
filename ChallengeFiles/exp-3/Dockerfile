FROM amazoncorretto:8-alpine-full

RUN echo "nobody:x:65534:65534:Nobody:/:" > /etc/passwd

COPY ./docker-files/ .

USER nobody

CMD [ "java", "-jar", "./Server.jar", "8001" ]