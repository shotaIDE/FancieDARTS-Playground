FROM centos:7.5.1804

RUN yum update -y && \
    rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-6.noarch.rpm

RUN yum install -y \
    httpd

RUN yum install -y \
    php \
    php-mysql \
    php-devel \
    php-pecl-memcache \
    php-xml \
    gcc && \
    pecl install xdebug-2.2.7

COPY ./apache/darts.conf /etc/httpd/conf.d/
COPY ./php/xdebug.ini /etc/php.d/

EXPOSE 80

ENTRYPOINT ["httpd", "-D", "FOREGROUND"]
