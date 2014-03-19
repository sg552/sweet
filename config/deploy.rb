# -*- encoding : utf-8 -*-
require 'capistrano-rbenv'
set :rake, "bundle exec rake"
set :application, "weixin.pe"
#set :repository, "."
#set :scm, :none
#set :deploy_via, :copy
set :scm, :git
set :repository, 'git@github.com:sg552/sweet.git'
TEST_SERVER= "siwei.me"
PRODUCTION_SERVER = "weixin.pe"

set(:server_type) {
  puts "== 测试服务器是：  siwei.me"
  puts "== 正式服务器是：  weixin.pe "
  Capistrano::CLI.ui.ask("== which server do you want to deploy to? (test/production)? ")
}
case server_type.chomp
  when 'test'
    server = TEST_SERVER
  when 'production'
    server = PRODUCTION_SERVER
end

role :web, server
role :app, server
role :db,  server, :primary => true
role :db,  server

set :deploy_to, "/opt/weixin.pe"
default_run_options[:pty] = true

# change to your username
set :user, "root"

namespace :deploy do
  task :start do
    run "/etc/init.d/php5-fpm start"
  end
  task :stop do
    run "/etc/init.d/php5-fpm stop"
  end
  task :restart, :roles => :app, :except => { :no_release => true } do
    stop
    sleep 0.5
    start
  end

  namespace :assets do
    task :precompile do
  #    puts "======= should run precompile"
  #    command = "cd #{release_path} && bundle exec rake RAILS_ENV=production RAILS_GROUPS=assets assets:precompile "
  #    puts "== please run == \n #{command} \n == manually after deploy is done"
      #run "bundle install"
      #run "cd #{release_path} && bundle exec rake RAILS_ENV=production RAILS_GROUPS=assets assets:precompile "
    end
  end
end


desc "Copy database files to release_path"
task :copy_configure_files do

  puts "=== executing my customized command: "
  run "cp #{shared_path}/Conf/*.php #{release_path}/upload/Conf/"
  run "chmod -R 777 #{shared_path}"
  run "chmod -R 777 #{release_path}"
  run "rm #{release_path}/upload/Conf/logs -rf"
  run "ln -s #{shared_path}/Conf/logs #{release_path}/upload/Conf/logs"
  run "ln -s #{shared_path}/uploads #{release_path}/upload/uploads"
end

after "deploy:update_code", :copy_configure_files
