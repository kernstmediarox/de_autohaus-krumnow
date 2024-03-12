Server verbinden
de_autohaus-krumnow
cd html/wordpress/

Datenbank dumpen
cat wp-config.php | grep 'DB_PASSWORD'
mysqldump --single-transaction -udb-user-2 -p db-2 > current.sql

Wordpress packen
tar cfvz /var/www/share/backups/de_messerspezialist/blog.tar.gz ../blog

Lokal Aktualisieren
cd /home/data/www/blog_messerspezialist
rsync -avzp --exclude "wp-config.php" de_autohaus-krumnow:html/wordpress/ .
mysql -uroot -p de_autohaus-krumnow < current.sql

Datenbank Einstellungen
siteurl und home anpassen
UPDATE `wp_options` SET `option_value` = 'https://www.autohaus-krumnow.local' WHERE `wp_options`.`option_id` = 1;
UPDATE `wp_options` SET `option_value` = 'https://www.autohaus-krumnow.local' WHERE `wp_options`.`option_id` = 2; 