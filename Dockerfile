FROM ubuntu:22.04
    
    RUN apt update -y && apt upgrade -y
    RUN apt-get install -y tzdata 
    #Konum icin input istiyor Input girmeden geciyoruz.
    RUN apt install -y nginx\
    php8.1\
    php-fpm\
    php8.1-xml\
    freeradius\
    freeradius-utils\
    freeradius-mysql\
    freeradius-mysql\
    nano\
    mysql-server\
    sqlite3

    RUN apt-get install -y php8.1-mysql
    RUN apt-get install -y iputils-ping
    RUN apt-get install -y dos2unix
    RUN apt-get install -y curl
    RUN apt install php-dapphp-radius
    RUN apt-get install php-common php-gd php-curl php-mysql -y
    RUN apt-get install mysql-server mysql-client -y
    RUN apt-get install net-tools tree 
    RUN apt-get install cron -y
    RUN apt install php-soap -y
	RUN apt install composer -y
    COPY ./nginx/default.conf /etc/nginx/sites-available/default

    COPY ./radius/sql /etc/freeradius/3.0/mods-available/sql
    COPY ./radius/default /etc/freeradius/3.0/sites-available/default
    # COPY ./radius/default /etc/freeradius/3.0/mods-available/default
    COPY ./radius/clients.conf /etc/freeradius/3.0/clients.conf
    COPY ./www/html/config.json /var/www/html/config.json
    COPY ./var/log/system.log /var/log/system.log
    COPY ./www/html/mailer /usr/share
    COPY ./www/html/mailer/mailer.cron /etc/cron.d/mailer
	COPY ./www/html/mailer/migrate.sh /etc/migrate.sh    

    COPY ./configsFile/configg.sh /home/
    COPY ./mysql/dbconfig.sh /home/dbconfig.sh
    # COPY ./www/html /var/www/html

    RUN ln -s /etc/freeradius/3.0/mods-available/sql /etc/freeradius/3.0/mods-enabled/sql

    RUN echo "* * * * * /usr/bin/php /var/www/html/timed.php" > /var/spool/cron/crontabs/root
    RUN chmod 0644 /var/spool/cron/crontabs/root 
    RUN chmod 777 /var/www/html/config.json
    RUN chmod 777 /var/log/system.log
    #izin hatali


    RUN chmod +x /home/dbconfig.sh     
    RUN dos2unix /home/dbconfig.sh
    RUN sh /home/dbconfig.sh
	RUN mkdir -vp /var/sqlite
	RUN sh /etc/migrate.sh /var/sqlite/mail.db

    RUN chmod +x /home/configg.sh     
    RUN dos2unix /home/configg.sh
    RUN sh /home/configg.sh
    EXPOSE 80
    CMD [ "/home/configg.sh" ]
