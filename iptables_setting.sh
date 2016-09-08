#!/bin/sh

#停止firewalld服务
systemctl stop firewalld
#禁用firewalld服务
systemctl mask firewalld

yum install -y iptables iptables-services

file=/etc/sysconfig/iptables
echo $file
if [ -f $file ]
then
mv $file $file-bak
fi

cat > $file <<EOF
# sample configuration for iptables service
# you can edit this manually or use system-config-firewall
# please do not ask us to add additional ports/services to this default configuration
*filter
:INPUT ACCEPT [0:0]
:FORWARD ACCEPT [0:0]
:OUTPUT ACCEPT [0:0]
-A INPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
-A INPUT -p icmp -j ACCEPT
-A INPUT -i lo -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 22 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 80 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 62001:62099 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 60001:60099 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 3309:3399 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 61001:61010 -j ACCEPT
-A INPUT -j REJECT --reject-with icmp-host-prohibited
-A FORWARD -j REJECT --reject-with icmp-host-prohibited
COMMIT

EOF

systemctl restart iptables.service
systemctl enable iptables.service

