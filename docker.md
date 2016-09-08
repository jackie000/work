数据卷容器
docker run -d -ti --name soft -v /data/soft:/data/soft centos:centos7

docker run -d -ti --name www -v /data/www:/data/www centos:centos7

docker run -dti --volumes-from soft --volumes-from www --privileged=true --name php7 centos:centos7


