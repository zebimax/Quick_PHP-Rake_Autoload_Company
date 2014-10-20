Quick_PHP-Rake_Autoload_Company
===============================

Quick in setting and simple in usage autoloader with classmap genereted by rake command. Useful for user testing projects.
Basic usage
Put files Rakefile, classmap_generator.php, AutoLoader.php in your project directory.

in index file:

require_once('AutoLoader.php'); spl_autoload_register('AutoLoader::autoLoad');

And before launch the application run rake in app. directory.