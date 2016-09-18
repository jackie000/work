#!/bin/sh

redis_version=redis-3.2.3
redis_conf=/etc/myconf/$redis_version
redis_path=/opt/application/$redis_version

soft_dir=/data/soft/redis
mkdir -p $soft_dir
cd $soft_dir


if [ ! -f $soft_dir/$redis_version.tar.gz ]
then
wget http://download.redis.io/releases/redis-3.2.3.tar.gz
fi

if [ ! -d $soft_dir/$redis_version ]  &&  [ -e $soft_dir/$redis_version.tar.gz ]
then
tar xzvf $redis_version.tar.gz -C /opt/application
cd $redis_path

make
make install

mkdir -p $redis_conf
sed -i "128s/no/yes/g" redis.conf
sed -i "84s/6379/60001/g" redis.conf
cp redis.conf $redis_conf

echo never > /sys/kernel/mm/transparent_hugepage/enabled
echo 511 > /proc/sys/net/core/somaxconn

cat > /usr/lib/systemd/system/redis60001.service <<EOF
[Unit]
Description=redis
Documentation=
After=network.target remote-fs.target nss-lookup.target

[Service]
Type=forking
#PIDFile=/tmp/nginx.pid
ExecStart=$redis_path/src/redis-server $redis_conf/redis.conf
ExecStop=/bin/kill -INT \$MAINPID
PrivateTmp=true

[Install]
WantedBy=multi-user.target

EOF

systemctl start redis60001.service
systemctl enable redis60001.service
fi

