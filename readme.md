WordPress Theme Check
=====================

This is a fork of the [WordPress Theme Check Plugin](http://wordpress.org/plugins/theme-check/), extended to run from the command line, or from ant. It wraps the output from the plugin in a Bootstrap template for ease of reading outside of WordPress. The reason for doing this was so I could build the report and checks state into our Jenkins build pipeline. 

CLI
---

You can call theme check from the command line by in the following format:

    $ php cli.php --wordpress=/path/to/wordpress/core --folder=/path/to/themes/folder theme_name

Additionally, you can parse that report into a JUnit-style xml file with the included `junit-output.php` utility.

    $ php cli/junit-output.php [--threshold=0] /path/to/report

The script simply counts the "Required" items returned in the report, and will output a failure state if it's greater then the threshold parameter.

Build Script
------------

A `build.xml` script can be used to call the script from a ant (usually within a build system). The same parameters can be passed in like so:

    $ ant -Dtheme_name=theme_name -Dwordpress_dir=/path/to/wordpress/core -Dtheme_dir=path/to/themes/folder

This will create a `results/html` and `results/xml` folder and put the theme report and the JUnit xml file in those respective directories.
