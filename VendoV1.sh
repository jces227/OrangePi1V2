#!/bin/bash
#curl -sL https://raw.githubusercontent.com/jces227/OrangePi1V1/main/VendoV1.sh | bash
#echo "LAN Rules..."
#sudo rm /etc/udev/rules.d/10-lan0.rules
#sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/10-lan0.rules -o /etc/udev/rules.d/10-lan0.rules
#sudo udevadm control --reload

echo "Updating System"
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl
sudo apt install -y wget
sudo apt install -y git
sudo apt install -y nano
sudo apt install -y htop
sudo apt install -y ca-certificates
sudo apt install -y openssl
sudo apt install -y gnupg
sudo apt install -y lsb-release

echo "Installing Ngix"
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx

sudo apt install -y php-fpm
sudo apt install -y php-cli
sudo apt install -y php-curl
sudo apt install -y php-json
sudo apt install -y php-mbstring
sudo apt install -y php-xml
sudo apt install -y php-zip

pip3 install requests
sudo apt install -y supervisor
sudo apt install -y dphys-swapfile
sudo systemctl enable dphys-swapfile

echo "10-dhcp-all-interfaces.yaml Downloading..."
sudo rm /etc/netplan/10-dhcp-all-interfaces.yaml
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/10-dhcp-all-interfaces.yaml -o /etc/netplan/10-dhcp-all-interfaces.yaml
sudo chmod 600 /etc/netplan/10-dhcp-all-interfaces.yaml

echo "net.ipv4.ip_forward=1" > /etc/sysctl.d/99-router.conf
sudo sysctl --system
netplan apply

sudo apt update
sudo DEBIAN_FRONTEND=noninteractive apt install -y iptables iptables-persistent

sudo iptables -t nat -A POSTROUTING -o end0 -j MASQUERADE

sudo iptables -A FORWARD -i end0 -o lan0 -m state --state RELATED,ESTABLISHED -j ACCEPT

sudo iptables -A FORWARD -i lan0 -o end0 -j ACCEPT

sudo netfilter-persistent save

sudo apt install dnsmasq -y

sudo mv /etc/dnsmasq.conf /etc/dnsmasq.conf.bak

sudo systemctl stop systemd-resolved

sudo systemctl disable systemd-resolved

sudo rm /etc/resolv.conf

sudo echo -e "nameserver 8.8.8.8\nnameserver 8.8.4.4" | sudo tee /etc/resolv.conf

sudo rm /etc/dnsmasq.conf

sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/dnsmasq.conf -o /etc/dnsmasq.conf

sudo systemctl enable dnsmasq
sudo systemctl restart dnsmasq

#ip addr show lan0
#ip addr show end0

sudo rm /etc/sysctl.d/99-router.conf

sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/99-router.conf -o /etc/sysctl.d/99-router.conf

sudo sysctl --system

sudo sysctl net.ipv4.ip_forward

sudo iptables -t nat -L -n -v

sudo apt install iptables-persistent -y
sudo netfilter-persistent save

echo "Installing Admin Portal..."
sudo apt install apache2 php -y

sudo mkdir /var/www/html/admin
sudo chown -R www-data:www-data /var/www/html/admin
sudo mkdir -p /var/www/html/admin/css
sudo mkdir -p /var/www/html/admin/uploads

sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/admin/config.json -o /var/www/html/admin/config.json
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/admin/dashboard.php -o /var/www/html/admin/dashboard.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/admin/index.php -o /var/www/html/admin/index.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/admin/logout.php -o /var/www/html/admin/logout.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/admin/css/style.css -o /var/www/html/admin/css/style.css
sudo rm /etc/nginx/sites-enabled/default
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/default -o /etc/nginx/sites-enabled/default

sudo systemctl restart php8.4-fpm
sudo systemctl restart nginx

sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html

echo "Installing Client Portal..."
sudo mkdir -p /var/www/html/portal
sudo chown -R www-data:www-data /var/www/html/portal
sudo chmod 644 /var/www/html/admin/config.json
sudo mkdir -p /var/www/html/portal/css
sudo mkdir -p /var/www/html/portal/api
sudo mkdir -p /var/www/html/portal/js

sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/config_loader.php -o /var/www/html/portal/config_loader.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/index.php -o /var/www/html/portal/index.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/css/style.css -o /var/www/html/portal/css/style.css
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/api/get_session.php -o /var/www/html/portal/api/get_session.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/api/get_status.php -o /var/www/html/portal/api/get_status.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/js/app.js -o /var/www/html/portal/js/app.js
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/check_vendo.php -o /var/www/html/portal/check_vendo.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/release_vendo.php -o /var/www/html/portal/release_vendo.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/api/start_session.php -o /var/www/html/portal/api/start_session.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/api/omada.php -o /var/www/html/portal/api/omada.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/success.html -o /var/www/html/portal/success.html

sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/index.php -o /var/www/html/index.php

sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/get_coins.php -o /var/www/html/portal/get_coins.php
sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/portal/reset_coins.php -o /var/www/html/portal/reset_coins.php

sudo chown -R www-data:www-data /var/www/html/admin/uploads/
sudo chmod -R 755 /var/www/html/admin/uploads/

sudo chown www-data:www-data /var/lib/misc/dnsmasq.leases
sudo chmod 644 /var/lib/misc/dnsmasq.leases

echo "Coins daemon installed..."
sudo apt update
sudo apt install python3-venv python3-full

sudo mkdir -p /opt/coin_env
sudo python3 -m venv /opt/coin_env

source /opt/coin_env/bin/activate

pip install OPi.GPIO

sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/coin_daemon.py -o /usr/local/bin/coin_daemon.py
sudo chmod +x /usr/local/bin/coin_daemon.py

#sudo /opt/coin_env/bin/python /usr/local/bin/coin_daemon.py

sudo touch /var/www/html/portal/coins.txt
sudo chown www-data:www-data /var/www/html/portal/coins.txt
sudo chmod 666 /var/www/html/portal/coins.txt

sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/main/coin.service -o /etc/systemd/system/coin.service

sudo systemctl daemon-reload
sudo systemctl enable coin.service
sudo systemctl start coin.service

sudo reboot

#sudo systemctl daemon-reload
#sudo systemctl enable coin_daemon.service
#sudo systemctl start coin_daemon.service




#echo "Firewall Setup.."
#sudo iptables -P INPUT DROP
#sudo iptables -P FORWARD DROP
#sudo iptables -P OUTPUT ACCEPT

#sudo iptables -A INPUT -i lo -j ACCEPT

#sudo iptables -A INPUT -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
#sudo iptables -A FORWARD -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT

#sudo iptables -A INPUT -i lan0 -j ACCEPT

#sudo iptables -A FORWARD -i lan0 -o end0 -j ACCEPT

#sudo netfilter-persistent save








#sudo mkdir /var/www/html/admin
#sudo chown -R www-data:www-data /var/www/html/admin

# Download files
#sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/tree/main/admin/dashboard.php -o /var/www/html/admin/dashboard.php
#sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/tree/main/admin/index.php -o /var/www/html/admin/index.php
#sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/tree/main/admin/logout.php -o /var/www/html/admin/logout.php
#sudo curl -L https://raw.githubusercontent.com/jces227/OrangePi1V1/tree/main/admin/style.css -o /var/www/html/admin/style.css

echo "Done! Your website is installed."

