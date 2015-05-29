# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "chef/debian-7.4"
  config.vm.network "private_network", ip: "192.168.3.2"
  config.vm.synced_folder ".", "/var/www", type: "nfs"
  config.vm.provision :shell, :path => "provision.sh"
end
