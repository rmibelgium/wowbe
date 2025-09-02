# WOW-BE AWS Synchronization script Installation Guide

## Build PHAR

See <https://box-project.github.io/box/>

```bash
composer install
composer bin box install
vendor/bin/box compile
```

## Install Oracle OCI driver

```bash
apt install libaio1
mkdir /opt/oracle/
cd /opt/oracle/
wget https://download.oracle.com/otn_software/linux/instantclient/2390000/instantclient-basiclite-linux.x64-23.9.0.25.07.zip
unzip instantclient-basiclite-linux.x64-23.9.0.25.07.zip
wget https://download.oracle.com/otn_software/linux/instantclient/2390000/instantclient-sdk-linux.x64-23.9.0.25.07.zip
unzip instantclient-sdk-linux.x64-23.9.0.25.07.zip
ln -s instantclient_23_9/ instantclient
pecl install pdo_oci # Enter `instantclient,/opt/oracle/instantclient` when asked.
echo extension=pdo_oci.so > /etc/php/8.4/mods-available/pdo_oci.ini
echo "/opt/oracle/instantclient" > /etc/ld.so.conf.d/oracle-instantclient.conf
ldconfig
phpenmod pdo_oci
```
