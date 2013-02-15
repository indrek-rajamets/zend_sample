Zend sample application
=======================

Introduction
------------
This is a simple, we are using a sceletop application to build
as simple application to manage students, homeworks and grades.


Installation
------------
Download Zend Framework 2 and extract it.
Set up enviroment constant, which points to zend library:

SetEnv ZF2_PATH "/usr/share/php/zf2/library"

Create your virtualhost configuration or modify .htaccess as follows:

<VirtualHost *:80>
    ServerName localhost.zend
    DocumentRoot */ZendSample/public
    SetEnv APPLICATION_ENV "development"
    SetEnv ZF2_PATH "/usr/share/php/zf2/library"
    <Directory */ZendSample/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>

Checkuot this repository to your document root (*/ZendSample/public).

