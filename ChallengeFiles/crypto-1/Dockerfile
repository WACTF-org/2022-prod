FROM python:3-alpine

RUN echo "nobody:x:65534:65534:Nobody:/:" > /etc_passwd
RUN apk add -U g++ gcc

COPY ./docker-files/ .

RUN pip install --no-cache-dir -r requirements.txt

USER nobody

CMD [ "python", "./server.py" ]