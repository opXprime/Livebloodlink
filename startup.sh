#!/bin/bash
sed -i 's|/home/site/wwwroot/index.php|/home/site/wwwroot|g' /etc/nginx/sites-available/default
sed -i 's|index  index.php index.html index.htm hostingstart.html;|index  index.php index.html;|g' /etc/nginx/sites-available/default
service nginx reload