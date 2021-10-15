FROM nginx:alpine

COPY docker/nginx/conf/app.template.conf /etc/nginx/conf.d/default.conf.template

COPY docker/nginx/docker-entrypoint.sh /docker-entrypoint.d/set-conf.sh
RUN ["chmod", "+x", "/docker-entrypoint.d/set-conf.sh"]
#RUN ["sh", "/docker-entrypoint.d/set-conf.sh"]
#ENTRYPOINT ["sh", "/docker-entrypoint.d/set-conf.sh"]
CMD ["nginx", "-g", "daemon off;"]

