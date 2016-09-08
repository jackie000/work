#!/bin/sh

yum -y install gcc gcc-c++ autoconf automake
yum -y install zlib zlib-devel openssl openssl-devel pcre-devel

nginx_version=nginx-1.10.1
nginx_path=/opt/application/$nginx_version
nginx_conf=/etc/myconf/$nginx_version

mkdir -p $nginx_path
mkdir -p $nginx_conf

soft_dir=/data/soft/nginx
cd $soft_dir

nginx_user=nginx
groupadd $nginx_user
useradd $nginx_user -g $nginx_user -M -s /sbin/nologin

if [ ! -d $soft_dir/$nginx_version ]  &&  [ -e $soft_dir/$nginx_version.tar.gz ]
then
tar xzvf $nginx_version.tar.gz
cd $soft_dir/$nginx_version

./configure --prefix=$nginx_path --conf-path=$nginx_conf/nginx.conf  --user=$nginx_user --group=$nginx_user

make && make install
fi

cat > /usr/lib/systemd/system/nginx.service <<EOF

[Unit]
Description=nginx server
After=network.target remote-fs.target nss-lookup.target

[Service]
Type=forking
ExecStartPre=$nginx_path/sbin/nginx -t
ExecStart=$nginx_path/sbin/nginx
ExecReload=/bin/kill -s HUP \$MAINPID
ExecStop=/bin/kill -s QUIT \$MAINPID
PrivateTmp=true

[Install]
WantedBy=multi-user.target

EOF

cat > $nginx_conf/nginx.conf <<EOF
user  $nginx_user;
worker_processes  auto;

error_log  logs/error.log;
#error_log  logs/error.log  notice;
#error_log  logs/error.log  info;

#pid        logs/nginx.pid;
worker_rlimit_nofile 100000;



events {
    use epoll;
    worker_connections  100000;
}


http {
    include       mime.types;
    default_type  application/octet-stream;

    log_format  main  '\$remote_addr - \$remote_user [\$time_local] "\$request" '
                      '\$status \$body_bytes_sent "\$http_referer" '
                      '"\$http_user_agent" "\$http_x_forwarded_for"';

    access_log  logs/access.log  main;

    sendfile        on;
    tcp_nopush     on;

    #keepalive_timeout  0;
    keepalive_timeout  30;

    client_header_buffer_size 4k;

    open_file_cache max=100000 inactive=10s;
    open_file_cache_valid 30s;
    open_file_cache_min_uses 1;
    reset_timedout_connection on;


    gzip  on;
    gzip_min_length  1k;
    gzip_buffers     4 16k;
    gzip_http_version 1.0;
    gzip_comp_level 4;
    gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript;

    fastcgi_cache_path $nginx_path/logs levels=1:2 keys_zone=cache_fastcgi:128m inactive=1m;
    fastcgi_connect_timeout 300;
    fastcgi_send_timeout 300;
    fastcgi_read_timeout 300;
    fastcgi_buffer_size 32k;
    fastcgi_buffers 4 32k;
    fastcgi_busy_buffers_size 64k;
    fastcgi_temp_file_write_size 64k;

    include $nginx_conf/vhost/*.conf;

}
EOF

ip=`ifconfig |  grep 'inet' | grep -v 'grep' | awk '{print $2}' | paste -s -d " "`
mkdir -p /data/www
chown -R $nginx_user:$nginx_user /data/www
echo $ip > /data/www/ip.html

cat > $nginx_conf/vhost/www.conf <<EOF
server {
	listen       80;
	server_name  $ip;

	error_log  logs/localhost.error.log;
	root   /data/www;

	location / {
	    root   /data/www;
	    index  index.html index.htm index.php;
	}

	error_page   500 502 503 504  /50x.html;
	location = /50x.html {
	    root   html;
	}

	location ~ \.php$ {
	    root           /data/www;
	    fastcgi_pass   unix:/dev/shm/php-cgi.sock;
	    fastcgi_index  index.php;
	    fastcgi_param  SCRIPT_FILENAME  \$document_root\$fastcgi_script_name;
	    include        fastcgi_params;
	}

}
EOF

systemctl daemon-reload
systemctl restart nginx.service
systemctl enable nginx.service

