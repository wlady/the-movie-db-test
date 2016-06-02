# How to install


PHP and [Composer](https://getcomposer.org/) should be installed in the OS.  On my local machine I've been used the directory “test”  as project root directory and “public_html” as DOCUMENT_ROOT.

  - Clone the project into "test"
  - Go to "test" directory
  - run "composer install"
  - Go to to "public_html/protected" directory
  - run "./yiic migrate up"