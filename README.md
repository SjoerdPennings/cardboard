# 📦 `cardboard`

My personal vagrant dev box.

## What's in the box?

- Ubuntu 20.04 LTS
- PHP 7.3
- MySQL
- Apache
- NodeJS 15
- Oh-My-ZSH with Powerlevel10k theme
- vagrant-hostmanager

## Install

1. Install Vagrant and Virtualbox.
2. Run `vagrant up`
3. All done! Go to `cardboard.box` to see your website!

## Folders and files

- `custom.sh` - Place any custom provisioning commands here.
- `/dotfiles` - Place your dotfiles here to copy them over to the box's ~ folder every time you start the box.
- `/www` - This folder is automatically synced to the box's /var/www folder, so place your website here.
