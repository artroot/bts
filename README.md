# Koala Tracker <img src="http://i.piccy.info/i9/d1b00ff2aea0558b3a418eab12ddca10/1526591944/3008/1245025/koala_logo.png"/>

The Koala bug tracking system supported telegram notification and telegram bot management, plus have a simple prototype upload and view.

# Usage via Docker

```
docker run -p 80:80 artroot/koala:lts
```
NOTE: default login parameters for web is `user - admin`, `pass - koala`; And you must change default password after loginned;

# Install via composer

1. Clone repository
```
git clone https://github.com/artroot/koala.git
```

2. Install requairments
```
cd ./koala && composer install
```

3. Create a DataBase and User. 
Enter details for connecting to the database into configuration file `config/db.php` 

4. Complete migrations
```
php yii migrate --interactive=0
```
