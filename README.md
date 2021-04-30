1. Go to https://worpress.org download and install the latest Wordpress
2. Clone repo into the root's directory
3. Open `wp-content/themes/iqwik` and copy `version.php.EXAMPLE` to `version.php` in the same folder
4. Go to admin panel:
```
    -> Appearance 
        -> Themes 
            -> choose and activate "iqwik" theme
```
5. Set up SMTP-mail into `./wp-config.php`, copy and paste the code below (update on yours data):
```
    // SMTP
    define( 'SMTP_USER', 'example@mail.com' );
    define( 'SMTP_PASS', '123456' );
    define( 'SMTP_HOST', 'smtp.mail.com' );
    define( 'SMTP_FROM', 'example@mail.com' );
    define( 'SMTP_NAME', 'YourWebsite' );
    define( 'SMTP_PORT', 465 );
    define( 'SMTP_SECURE', 'ssl' );
    define( 'SMTP_AUTH', true );
    define( 'SMTP_DEBUG', 0 ); // Levels (0, 1, 2)   
```
6. Optionally (for develop):
    - Set up your localhost for proxy into `webpack/proxyServer.json`, then run terminal and execute:
```
yarn
yarn dev
yarn build
```
