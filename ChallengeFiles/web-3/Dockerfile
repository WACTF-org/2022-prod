FROM ruby:3.0.2-alpine

RUN adduser -D -s /bin/false rails

ADD --chown=rails docker-files/ /swansay
WORKDIR /swansay

RUN apk add build-base

USER rails

RUN gem install bundler:2.3.5
RUN bundle install

ENV RAILS_ENV production
ENV RAILS_SERVE_STATIC_FILES true
RUN rails assets:precompile

EXPOSE 80

CMD ["rails", "server", "-p", "80", "-b", "0.0.0.0", "-e", "production"]

