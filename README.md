## Create

Dependencies
```
PHP >= 7.1.3
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
XML PHP Extension
Ctype PHP Extension
JSON PHP Extension
PostgreSQL
```

For create database

```bash
#!/bin/bash

DB="library"
USER="forex"
PASSWORD="password"

sudo -u postgres psql -c "REVOKE ALL PRIVILEGES ON ALL TABLES IN SCHEMA public FROM $USER;"
sudo -u postgres psql -c "REVOKE ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public FROM $USER;"
sudo -u postgres psql -c "REVOKE ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA public FROM $USER;"
sudo -u postgres psql -c "DROP OWNED BY $USER;"
sudo -u postgres psql -c "DROP USER $USER;"
sudo -u postgres psql -c "DROP DATABASE $DB;"

sudo -u postgres createuser $USER
sudo -u postgres createdb $DB
sudo -u postgres psql -c "alter user $USER with encrypted password '$PASSWORD';"
sudo -u postgres psql -c "grant all privileges on database $DB to $USER;"
```

For install package dependences

```bash
cd /var/www
git clone https://github.com/kamaliev/library.git
cd library
composer install
./artisan migrate
```

## Next
Configure your NGINX or Apache 

## API Routes

> [cds] can be replaced to [books]

Create Book or Cd
``` 
POST api/v1/scan
```
---

Get top from authors
```
GET api/v1/cds/top/{limit}
```
---

Get cds between from and to years
```
GET api/v1/cds/between/{yearFrom}/{yearTo}
```
---

Get cds beafore year
```
GET api/v1/cds/before/{before}
```
---

Get cds after year
```
GET api/v1/cds/after/{after}
````
---

Get CDs albums average per years
```
GET api/v1/cds/author/average
GET api/v1/cds/author/average?author=Би-2
```
---
Get author albums
```
GET api/v1/cds/author?author=Би
```

## Logs

```
cd /var/www/library/storage/logs/scanner.log
```

## Test

```bash
cd /var/www/library
./vendor/bin/phpunit
```
