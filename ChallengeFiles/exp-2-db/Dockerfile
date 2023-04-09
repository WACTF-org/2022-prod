FROM alpine:latest

ARG BUILD_DATE
ARG BUILD_REF
ARG BUILD_VERSION
ARG APK_VERSION="10.6.11-r0"

SHELL ["/bin/ash", "-euo", "pipefail", "-c"]

RUN \
  apk add --no-cache mariadb=${APK_VERSION} mariadb-client=${APK_VERSION} && \
  TO_KEEP=$(echo " \
    etc/ssl/certs/ca-certificates.crt$ \
    usr/bin/mariadbd$ \
    usr/bin/getconf$ \
    usr/bin/getent$ \
    usr/bin/mariadb-install-db$ \
    usr/share/mariadb/charsets \
    usr/share/mariadb/english \
    usr/share/mariadb/mysql_system_tables.sql$ \
    usr/share/mariadb/mysql_performance_tables.sql$ \
    usr/share/mariadb/mysql_system_tables_data.sql$ \
    usr/share/mariadb/maria_add_gis_sp_bootstrap.sql$ \
    usr/share/mariadb/mysql_sys_schema.sql$ \
    usr/share/mariadb/fill_help_tables.sql$" | \
    tr -d " \t\n\r" | sed -e 's/usr/|usr/g' -e 's/^.//') && \
  INSTALLED=$(apk info -q -L mariadb-common mariadb linux-pam ca-certificates | grep "\S") && \
  for path in $(echo "${INSTALLED}" | grep -v -E "${TO_KEEP}"); do \
    eval rm -rf "${path}"; \
  done && \
  touch /usr/share/mariadb/mysql_test_db.sql && \
  # this file is removed since we remove most things from mariadb-common
  echo "!includedir /etc/my.cnf.d" > /etc/my.cnf && \
  # allow anyone to connect by default
  sed -ie 's/127.0.0.1/%/' /usr/share/mariadb/mysql_system_tables_data.sql && \
  mkdir /run/mysqld && \
  chown mysql:mysql /etc/my.cnf.d/ /run/mysqld /usr/share/mariadb/mysql_system_tables_data.sql

# The ones installed by MariaDB was removed in the clean step above due to its large footprint
# my_print_defaults should cover 95% of cases since it doesn't properly do recursion
COPY sh/resolveip.sh /usr/bin/resolveip
COPY sh/my_print_defaults.sh /usr/bin/my_print_defaults
COPY sh/run.sh /run.sh
RUN chmod +x /run.sh && chmod +x usr/bin/my_print_defaults && chmod +x /usr/bin/resolveip
# Used in run.sh as a default config
COPY my.cnf /tmp/my.cnf

# copy over sql file
COPY exp-2.sql /docker-entrypoint-initdb.d/exp-2.sql
COPY exp-2-2.sql /docker-entrypoint-initdb.d/exp-2-2.sql

ENTRYPOINT ["/run.sh"]
EXPOSE 3306
