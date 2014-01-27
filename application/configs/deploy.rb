set   :application,         "med-optima"
set   :deploy_to,           "/var/www/med-optima"
set   :domain,              "med-optima.rademade.com"

set   :scm,                 :git
set   :repository,          "git@rademade.com:MedOptima.git"

role  :web,                 domain
role  :app,                 domain, :primary => true

set   :user,                "deploy"
set   :use_sudo,            false
set   :keep_releases,       3

set   :shared_children,     ["application/configs/base",
                             "public/s/_compress/css",
                             "public/s/_compress/js",
                             "public/imagecache",
                             "public/s/public/upload",
                             "library/RM",
                             "library/Zend"]

set   :copy_exclude,        [".git", ".DS_Store", ".gitignore", ".gitmodules", "Capfile"]
set   :normalize_asset_timestamps, false

after "deploy:update_code" do
  run "rm -rf #{latest_release}/public/s/_compress/css/* && chmod 777 #{latest_release}/public/s/_compress/css"
  run "rm -rf #{latest_release}/public/s/_compress/js/* && chmod 777 #{latest_release}/public/s/_compress/js"
  run "chmod 777 #{latest_release}/public/imagecache"
  run "chmod 777 #{latest_release}/public/s/public/upload"
  run "chmod 777 #{latest_release}/public/s/public/upload/images"
end