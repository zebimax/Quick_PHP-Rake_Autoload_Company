desc 'Init class map'
task :classmap do
    system "php classmap_generator.php"
puts "Class Map Generator end his work!"
end
desc "Create temp directories if not exist and clean"
task :clean do
    FileUtils.remove_dir 'tmp', true

    FileUtils.mkdir_p 'tmp', :mode => 0777
end

task :default => ['clean', 'classmap']