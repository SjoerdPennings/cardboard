# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
    config.vagrant.plugins = "vagrant-hostmanager"
    config.vm.box = "ubuntu/focal64"
    config.vm.hostname = "cardboard.box"
    config.vm.network "forwarded_port", guest: 80, host: 8008
    config.vm.network "forwarded_port", guest: 3306, host: 3306
    config.vm.network "forwarded_port", guest: 22, host: 2202
    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.synced_folder "www/", "/var/www/html"
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.ignore_private_ip = false
    config.hostmanager.include_offline = true
    config.vm.provider "virtualbox" do |vb|
        vb.name = "cardboard"
        vb.memory = "1024"
        vb.cpus = 2
    end
    
    
    #Provisioning
    config.vm.provision "base", type: "shell", inline: <<-SHELL
        clear
        export DEBIAN_FRONTEND=noninteractive 
        echo "┌─────────────────────────────────────────────────────────────────┐"
        echo "│                   Provisioning cardboard box                    │"
        echo "├─────────────────────────────────────────────────────────────────┤"
        echo "├─Updating Ubuntu...                                      (01/13)─┤"
        apt-get update >/dev/null 2>&1 && apt-get upgrade >/dev/null 2>&1

        echo "├─Installing base tools...                                (02/13)─┤"
        apt-get install -y vim make curl git neofetch zsh update-motd htop build-essential unzip >/dev/null 2>&1

        echo "├─Installing node and npm...                              (03/13)─┤"
        curl -sL https://deb.nodesource.com/setup_15.x | sudo -E bash - >/dev/null 2>&1
        apt-get install -y nodejs >/dev/null 2>&1

        echo "├─Installing Apache...                                    (04/13)─┤"
        apt-get install -y apache2 >/dev/null 2>&1
        echo "ServerName localhost" >> /etc/apache2/apache2.conf

        echo "├─Installing PHP...                                       (05/13)─┤"
        apt-get install -y php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath >/dev/null2>&1

        echo "├─Installing MySQL...                                     (06/13)─┤"
        debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
        debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
        apt-get -q -y install mysql-server >/dev/null 2>&1
        sed -i -e 's/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf
        SQL="GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root'; FLUSH PRIVILEGES;"
        mysql -uroot -proot -e "${SQL}" >/dev/null 2>&1
        mysql_tzinfo_to_sql /usr/share/zoneinfo >/dev/null 2>&1 | mysql -uroot -proot mysql >/dev/null 2>&1
        sed -ri '/\[mysqld\]/ a\ default-time-zone = \x27Europe/Amsterdam\x27' /etc/mysql/mysql.conf.d/mysqld.cnf

        echo "├─Installing composer...                                  (07/13)─┤"
        curl -sS https://getcomposer.org/installer -o composer-setup.php
        php composer-setup.php --install-dir=/usr/local/bin --filename=composer >/dev/null 2>&1
        rm -f composer-setup.php

        echo "├─Setting timezone...                                     (08/13)─┤"
        timedatectl set-timezone Europe/Amsterdam >/dev/null 2>&1
        apt-get install -y ntp >/dev/null 2>&1

        echo "├─Installing PHPMyAdmin...                                (09/13)─┤"
        debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean true'
        debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password root'
        debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password root'
        debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password root'
        debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2'
        apt-get install -y phpmyadmin >/dev/null 2>&1
        ln -s /etc/phpmyadmin/apache.conf /etc/apache2/sites-enabled/phpmyadmin.conf >/dev/null 2>&1
        service apache2 restart

        echo "├─Restarting services...                                  (10/13)─┤"
        service mysql restart
        service apache2 reload

        echo "├─Editing MOTD...                                         (11/13)─┤"
        sudo rm /etc/update-motd.d/*
        touch /etc/update-motd.d/00-motd
        echo "#!/bin/bash" >> /etc/update-motd.d/00-motd
        echo "neofetch" >> /etc/update-motd.d/00-motd
        sudo chmod +x /etc/update-motd.d/00-motd
        sudo /usr/sbin/update-motd >/dev/null 2>&1

        echo "├─Installing oh-my-zsh...                                 (12/13)─┤"
        su vagrant -c 'sh -c "$(wget -O- --quiet https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)">/dev/null 2>&1'
        rm -rf /home/vagrant/.oh-my-zsh/custom
        git clone --depth=1 https://github.com/romkatv/powerlevel10k.git ${ZSH_CUSTOM:-/home/vagrant/.oh-my-zsh/custom}/themes/powerlevel10k >/dev/null 2>&1
        cp /home/vagrant/.oh-my-zsh/templates/zshrc.zsh-template /home/vagrant/.zshrc
        sudo chsh -s /bin/zsh vagrant
        sed -i 's#ZSH_THEME=[^ ]*#ZSH_THEME="powerlevel10k/powerlevel10k"#g' /home/vagrant/.zshrc
        git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-/home/vagrant/.oh-my-zsh/custom}/plugins/zsh-autosuggestions >/dev/null 2>&1
        git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-/home/vagrant/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting >/dev/null 2>&1
        git clone https://github.com/zsh-users/zsh-completions.git ${ZSH_CUSTOM:-/home/vagrant/.oh-my-zsh/custom}/plugins/zsh-completions >/dev/null 2>&1
        sed -i 's#plugins=(git)#plugins=(zsh-autosuggestions zsh-completions zsh-syntax-highlighting git)#g' /home/vagrant/.zshrc
        autoload -U compinit >/dev/null 2>&1 && compinit >/dev/null 2>&1
        echo "├─Running custom provision script...                      (13/13)─┤"

        #Custom provisions are run from custom.sh
        sh /vagrant/custom.sh >/dev/null 2>&1

        

        echo "├─────────────────────────────────────────────────────────────────┤"
        echo "│                            All done!                            │"
        echo "├─────────────────────────────────────────────────────────────────┤"
        echo "├─Head to cardboard.box to view your website!                     │"
        echo "│                                                                 │"
        echo "├─Put your website in www/ on the host to sync it to /var/www     │"
        echo "├─Put your dotfiles in dotfiles/ on the host to sync it to ~      │"
        echo "│                                                                 │"
        echo "├─Ports:                                                          │"
        echo "├───HTTP                                               80 or 8008─┤"
        echo "├───SSH                                                22 or 2202─┤"
        echo "├───SQL                                                      3306─┤"
        echo "│                                                                 │"
        echo "├─Database Login:                                                 │"
        echo "├───Pass:                                                    root─┤"
        echo "├───User:                                                    root─┤"
        echo "└─────────────────────────────────────────────────────────────────┘"
        zsh
    SHELL
    config.vm.provision "file", run: "always", source: "./dotfiles/.", destination: "/home/vagrant"
    
end
