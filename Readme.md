# FreedomCore (aka Freedom.Net)

FreedomCore is a replica of Battle.net website created by Blizzard.  
My desire is full compliance between FreedomCore abd Battle.net.  
  
Because it is most likely to happen that the development of the project will be only on me, please do not expect for the instant correction of errors and bugs, as well as new features. Of course, I'll be correcting them and so on, but please understand me, I need time.
#Now a bit of boring stuff
Since this project is publicly available and in need of help in the development, I ask you to provide a link to the repository, if you're going to lay out this project on any other site other than http://community.trinitycore.org  
 
In addition, I ask you to respect my work and do not specify yourself as the sole developer of the project.  
Either specify all those who participated in the development, or just say nothing.

#System Requirements
>This project is likely to be able to work with older versions of programs, but support for these versions will not be provided
 - Apache 2.4.12 (Win64)
 - PHP 5.6.9 (PHP 7.0)
 - MySQL 5.7 Community Edition

### Installation
```sh
$ git clone https://github.com/darki73/FreedomCore.git .
$ mv Core/Configuration/Configuration.php.in mv Core/Configuration/Configuration.php
$ chmod 755 -R Cache
```

###Importing Database
- Create Database for Website (Name it as you want, but dont forget to change config)
- Import SQL from Installation/SQL folder in root folder of project
- You should import **only latest** SQL file (file name format **day-month-year-hour-minutes.sql**)

### Generating Icons for Webiste
- Icons are not provided with clean installation with FreedomCore
- You must generate them using **DBCExtractor** Tool provided in **Tools** folder
- Place Extracted MPQ Archive with all the icons inside **/Tools/DBCExtractor/data/** folder
- Run **Generate_Icons.php**

#Important Stuff
Since this project lets say in **Alpha** stage, im not going to support database updates.   
**What does it means?**  
The thing is, since its hard to memorize all the changes made to the core, and since im coding like 8 hours a day, im not going to create a separate file for each **DB** change i made (just for now, later ill switch to same scheme as **TrinityCore** uses for **World** updates). So its either full re-installation of **Database** or just dont try to download it.  

**What im trying to say is**
###Its not ready for 'production' yet!