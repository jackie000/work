#!/bin/sh

yum -y install libxml2-devel openssl-devel libcurl-devel libjpeg-devel libpng-devel freetype-devel libmcrypt-devel libmcrypt

php_version=php-7.0.9
php_path=/opt/application/$php_version
php_conf=/etc/myconf/$php_version
php_bin=$php_path/bin

soft_dir=/data/soft/php

cd $soft_dir
if [ ! -d $soft_dir/$php_version ]  &&  [ -e $soft_dir/$php_version.tar.gz ]
then

mkdir -p $php_path
mkdir -p $php_conf/php-fpm.d

tar xzvf $php_version.tar.gz
cd $soft_dir/$php_version

./configure --prefix=$php_path --exec-prefix=$php_path --bindir=$php_path/bin \
			      --sbindir=$php_path/sbin \
			      --includedir=$php_path/include \
			      --libdir=$php_path/lib/php \
			      --with-config-file-path=$php_conf \
			      --with-mcrypt \
			      --with-mhash \
			      --with-openssl \
			      --with-gd \
			      --with-iconv \
			      --with-zlib \
			      --enable-zip \
			      --enable-inline-optimization \
			      --disable-debug \
			      --disable-rpath \
			      --enable-shared \
			      --enable-xml \
			      --enable-bcmath \
			      --enable-shmop \
			      --enable-sysvsem \
			      --enable-mbregex \
			      --enable-mbstring \
			      --enable-ftp \
			      --enable-gd-native-ttf \
			      --enable-pcntl \
			      --enable-sockets \
			      --with-xmlrpc \
			      --enable-soap \
			      --without-pear \
			      --with-gettext \
			      --enable-session \
			      --with-curl \
			      --with-jpeg-dir \
			      --with-freetype-dir \
			      --enable-opcache \
			      --enable-fpm \
			      --with-fpm-user=nginx \
			      --with-fpm-group=nginx \
			      --without-gdbm \
			      --with-mysqli=mysqlnd \
			      --with-pdo-mysql=mysqlnd \
			      --disable-fileinfo
make && make install

groupadd nginx
useradd nginx -g nginx -M -s /sbin/nologin

#php-fpm conf
cp $php_path/etc/php-fpm.conf.default $php_conf/php-fpm.conf
sed -i "17s/;//" $php_conf/php-fpm.conf
sed -i "24s/;//" $php_conf/php-fpm.conf
echo "include=$php_conf/php-fpm.d/*.conf" >> $php_conf/php-fpm.conf

cp $php_path/etc/php-fpm.d/www.conf.default $php_conf/php-fpm.d/www.conf
sed -i '36d' $php_conf/php-fpm.d/www.conf
sed -i '35a listen = /dev/shm/php-cgi.sock' $php_conf/php-fpm.d/www.conf
sed -i "47,49s/;//" $php_conf/php-fpm.d/www.conf

#php ini
cp php.ini-production $php_conf/php.ini


#php service
cat > /usr/lib/systemd/system/php7.service <<EOF
[Unit]
Description=php fpm cgi
Documentation=
After=network.target remote-fs.target nss-lookup.target

[Service]
Type=forking
#PIDFile=/tmp/nginx.pid
ExecStartPre=$php_path/sbin/php-fpm -t -y $php_conf/php-fpm.conf
ExecStart=$php_path/sbin/php-fpm -c $php_conf/php.ini -y $php_conf/php-fpm.conf
ExecStop=/bin/kill -INT \$MAINPID
PrivateTmp=true

[Install]
WantedBy=multi-user.target

EOF

systemctl start php7.service
systemctl enable php7.service
systemctl daemon-reload
#extension
sed -i "725a extension_dir='$php_path/lib/php/extensions/no-debug-non-zts-20151012/'" $php_conf/php.ini

echo "export PATH=$PATH:$php_path/bin/" >>/etc/profile
source /etc/profile

fi
#yaf
cd $soft_dir
yaf_version=yaf-3.0.3
if [ ! -d $soft_dir/$yaf_version ]  &&  [ -e $soft_dir/$yaf_version.tgz ]
then
tar xzvf $yaf_version.tgz
cd $soft_dir/$yaf_version
$php_bin/phpize
./configure --with-php-config=$php_bin/php-config
make && make install
sed -i '845a extension=yaf.so' $php_conf/php.ini
fi

#msgpack
cd $soft_dir
msgpack_version=msgpack-2.0.1
if [ ! -d $soft_dir/$msgpack_version ]  &&  [ -e $soft_dir/$msgpack_version.tgz ]
then
tar xzvf $msgpack_version.tgz
cd $soft_dir/$msgpack_version
$php_bin/phpize
./configure --with-php-config=$php_bin/php-config
make && make install
sed -i '850a extension=msgpack.so' $php_conf/php.ini
fi

#swoole
cd $soft_dir
swoole_version=1.8.7-stable
if [ ! -d $soft_dir/swoole-src-$swoole_version ]  &&  [ -e $soft_dir/$swoole_version.tar.gz ]
then
tar xzvf $swoole_version.tar.gz
cd $soft_dir/swoole-src-$swoole_version
$php_bin/phpize
./configure --with-php-config=$php_bin/php-config
make && make install
sed -i '845a extension=swoole.so' $php_conf/php.ini
fi

#yaconf
cd $soft_dir
if [ ! -d $soft_dir/yaconf ]  &&  [ -e $soft_dir/yaconf.tar.gz ]
then
tar xzvf yaconf.tar.gz
cd $soft_dir/yaconf
$php_bin/phpize
./configure --with-php-config=$php_bin/php-config
make && make install
sed -i '852a extension=yaconf.so' $php_conf/php.ini
fi


#redis
cd $soft_dir
redis_version=phpredis-php7
if [ ! -d $soft_dir/$redis_version ]  &&  [ -e $soft_dir/$redis_version.zip ]
then
unzip $redis_version.zip
cd $soft_dir/$redis_version
$php_bin/phpize
./configure --with-php-config=$php_bin/php-config
make && make install
sed -i '855a extension=redis.so' $php_conf/php.ini
fi

systemctl restart php7.service
























