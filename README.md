1. Go to https://worpress.org download and install the latest Wordpress
2. Put all files from repo in the root's directory
3. Go to `wp-content/themes/iqwik` and copy `version.php.EXAMPLE` (do not rename!) to `version.php` in those folder
4. Go to admin panel:
```
    -> Appearance 
        -> Themes 
            -> choose and activate theme iqwik
```
5. Open in browser `your-site.com/import.php`, then press `execute` button
6. The first thing, that you should do after import, go to admin panel (`your-site.ru/admin`):
```
    -> Settings 
        -> Permalinks 
            -> and set up Common Settings to Custom Structure: /%category%/%postname%/ 
    and Save Changes (press button)
```
7. Set up SMTP-mail, copy and paste the code below (update on yours data):
```
    // SMTP
    define( 'SMTP_USER', 'example@mail.com' );
    define( 'SMTP_PASS', '123456' );
    define( 'SMTP_HOST', 'smtp.mail.com' );
    define( 'SMTP_FROM', 'example@mail.com' );
    define( 'SMTP_NAME', 'Novagric' );
    define( 'SMTP_PORT', 465 );
    define( 'SMTP_SECURE', 'ssl' );
    define( 'SMTP_AUTH', true );
    define( 'SMTP_DEBUG', 0 ); // Levels (0, 1, 2)   
```
to `/wp-config.php` after line `define( 'WP_DEBUG', false );`

8. Optionally (for develop):
```
yarn
yarn upgrade
```
