FROM tomee:10-webprofile

RUN rm -rf /usr/local/tomee/webapps/ROOT /usr/local/tomee/webapps/ROOT.war

COPY build/libs/ROOT.war /usr/local/tomee/webapps/

EXPOSE 8080
CMD ["catalina.sh", "run"]
