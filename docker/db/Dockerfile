FROM postgres:13-alpine

COPY ./create_db.sh /docker-entrypoint-initdb.d/create_db.sh

RUN chmod +x /docker-entrypoint-initdb.d/create_db.sh
