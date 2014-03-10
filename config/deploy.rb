# -*- encoding : utf-8 -*-
require 'capistrano-rbenv'
set :rake, "bundle exec rake"
set :application, "weixin.pe"
set :repository, "."
set :scm, :none
set :deploy_via, :copy
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
    run "/etc/init.d/php5-fpm start"
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
task :cp_database_yml do
  #puts "=== executing my customized command: "
  #run "cp -r #{shared_path}/config/* #{release_path}/config/"
  #run "rm #{release_path}/public/files -rf"
  #run "ln -s #{shared_path}/files #{release_path}/public/files"
  #run "chmod -R 777 #{shared_path}"
  #run "touch #{release_path}/httparty.log && chmod 777 #{release_path}/httparty.log"
  #run "cp #{shared_path}/lib/radius/auth.py #{release_path}/lib/radius/"
  #if server_type.chomp.include? 'test'
  #  run "cp #{shared_path}/public/images/* #{release_path}/public/images"
  #  run "cp #{shared_path}/left.html.erb #{release_path}/app/views/users/"
  #end
  #run "chmod -R 777 /opt/app/ruby/m-cms/releases"
end

before "deploy:assets:precompile", :cp_database_yml
#after "deploy", "deploy:restart"
