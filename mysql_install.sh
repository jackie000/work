#!/bin/sh

mysql_version=mysql-5.7.9
boost_version=boost_1_59_0

soft_dir=/data/soft
cd $soft_dir

mysql_path=/opt/application/mysql-5.7.9
mysql_data=/data/mysql-5.7.9
mysql_cnf=/etc/myconf/mysql-5.7.9
mysql_pid=/var/run/mysql-5.7.9
mysql_log=/var/log/mysql-5.7.9

mysql_port=3309

if [ ! -d $soft_dir/$mysql_version ]
then

tar xzvf $mysql_version.tar.gz
tar xzvf $boost_version.tar.gz
cd $soft_dir/$mysql_version

yum -y install gcc-c++ ncurses-devel cmake make perl gcc autoconf automake zlib libxml libgcrypt libtool bison

groupadd mysql
useradd mysql -g mysql -M -s /sbin/nologin

mkdir -p $mysql_path
chown -R mysql.mysql $mysql_path
#data
mkdir -p $mysql_data
chown -R mysql.mysql $mysql_data

mkdir -p $mysql_cnf
#pid
mkdir -p $mysql_pid
chown -R mysql.mysql $mysql_pid

#log
mkdir -p $mysql_log

cmake -DCMAKE_INSTALL_PREFIX=$mysql_path -DMYSQL_DATADIR=$mysql_data \
 -DDOWNLOAD_BOOST=1 -DWITH_BOOST=../$boost_version -DSYSCONFDIR=$mysql_cnf \
 -DWITH_MYISAM_STORAGE_ENGINE=1 -DWITH_INNOBASE_STORAGE_ENGINE=1 \
 -DWITH_MEMORY_STORAGE_ENGINE=1 -DWITH_READLINE=1  \
 -DWITH_PARTITION_STORAGE_ENGINE=1 -DWITH_FEDERATED_STORAGE_ENGINE=1 \
 -DWITH_BLACKHOLE_STORAGE_ENGINE=1 -DENABLED_LOCAL_INFILE=1 \
 -DDEFAULT_CHARSET=utf8 -DDEFAULT_COLLATION=utf8_general_ci \
 -DWITH_EMBEDDED_SERVER=1 -DMYSQL_UNIX_ADDR=$mysql_path/mysql-3309.sock -DMYSQL_TCP_PORT=$mysql_port
 
make && make install
 
$mysql_path/bin/mysqld --initialize-insecure --user=mysql --basedir=$mysql_path --datadir=$mysql_data
 
cp support-files/my-default.cnf $mysql_cnf/my.cnf
cp support-files/mysql.server /etc/init.d/mysql-$mysql_port
chmod 0755 /etc/init.d/mysql-$mysql_port

cpucores=$( awk -F: '/model name/ {core++} END {print core}' /proc/cpuinfo )
ram=$( free -m | awk 'NR==2 {print $2}' )
buff_pool_size=$(echo "$ram/10*6"|bc)M
log_file_size=$(echo "$ram/16*2"|bc)M
innodb_thread_concurrency=$(echo "$cpucores*2"|bc)
innodb_read_io_threads=$(echo "$cpucores*4"|bc)
innodb_write_io_threads=$(echo "$cpucores*4"|bc)


cat > $mysql_cnf/my.cnf <<EOF
[client]
port            = $mysql_port
socket          = $mysql_path/mysql-$mysql_port.sock
[mysqld]
ft_min_word_len = 3
##GENERAL
user = mysql
port = $mysql_port
socket          = $mysql_path/mysql-$mysql_port.sock

pid-file=$mysql_pid/mysql-$mysql_port.pid
log-error = $mysql_log/mysql-$mysql_port-err.log
slow-query-log-file = $mysql_log/mysql-$mysql_port-slow.log
slow_query_log = 1


max_allowed_packet = 128M
sort_buffer_size = 2M
join_buffer_size = 4M
read_buffer_size = 2M
read_rnd_buffer_size = 4M
thread_cache_size = 16
query_cache_size= 128M
query_cache_limit = 16M
query_cache_type = 1
back_log = 1024
connect_timeout = 200
interactive_timeout = 300
tmp_table_size = 64M
max_heap_table_size = 64M
table_open_cache = 2000
open_files_limit = 65535
wait_timeout = 200

##InnoDB
innodb_buffer_pool_size = $buff_pool_size
innodb_log_file_size = $log_file_size
innodb_log_buffer_size = 128M
innodb_flush_log_at_trx_commit = 2
innodb_thread_concurrency = $innodb_thread_concurrency
innodb_flush_method=O_DIRECT
innodb_lock_wait_timeout=120
innodb_file_per_table
innodb_io_capacity=500
innodb_read_io_threads = $innodb_read_io_threads
innodb_write_io_threads = $innodb_write_io_threads
##For mysql >=5.6
innodb_buffer_pool_instances = 16
innodb_buffer_pool_dump_at_shutdown=1
innodb_buffer_pool_load_at_startup=1
innodb_buffer_pool_dump_now=1
innodb_buffer_pool_load_now=1
##add new
#innodb_io_capacity=2000
#innodb_io_capacity_max=6000
#innodb_lru_scan_depth=2000
#innodb_lock_wait_timeout = 7200

EOF

rm -rf /etc/my.cnf

cat > /usr/lib/systemd/system/mysql$mysql_port.service <<EOF
[Unit]
Description=mysql server $mysql_port
After=network.target remote-fs.target nss-lookup.target

[Service]
Type=forking
#PIDFile=/tmp/nginx.pid
ExecStart=/etc/init.d/mysql-$mysql_port start
ExecStop=/etc/init.d/mysql-$mysql_port stop
ExecReload=/etc/init.d/mysql-$mysql_port reload
PrivateTmp=true

[Install]
WantedBy=multi-user.target

EOF

echo "export PATH=$PATH:$mysql_path/bin/" >>/etc/profile
source /etc/profile

systemctl start mysql3309.service
systemctl enable mysql3309.service

$mysql_path/bin/mysqladmin -u root password R3JoC9MN4Ef0
$mysql_path/bin/mysql -uroot -pR3JoC9MN4Ef0 <<EOF
GRANT ALL PRIVILEGES ON *.* TO xjroot@'%' IDENTIFIED BY 'DgyE1ZHdMT5x' WITH GRANT OPTION;
FLUSH PRIVILEGES;
select * from mysql.user where user='xjroot'\G;
EOF

else
echo "installed.\n"

fi


