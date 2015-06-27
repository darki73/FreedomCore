# FreedomCore (aka Freedom.Net)

FreedomCore is a replica of Battle.net website created by Blizzard.  
My desire is full compliance between FreedomCore and Battle.net.  
  
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

#Supported Cores
>These Cores are supported so far  
 - TrinityCore 3.3.5a (Full Support, Tested)   
 - TrinityCore 4.3.4 (Full Support, Not tested)  
 - Skyfire 5.4.8 (Probably supported, if database structure is the same as TrinityCore)  
 - TrinityCore 6.x (Not supported, there is no table called **item_template** - so cant really get items info)  

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
- Place Extracted MPQ Archive with all the icons inside **/Tools/DBCExtractor/data/_Patch_Version_/interface/icons** folder
- Open in Browser **/Tools/DBCExtractor/** and select **Generate Icons** appropriate for your patch    

**Short video on how to extract icons (based on WoD 6.2 Patch) ** 

[![FreedomNet Icons Extractor](https://i.ytimg.com/vi/14o4nTLQ3aw/hqdefault.jpg)](http://www.youtube.com/watch?v=14o4nTLQ3aw)

#Important Stuff
Since this project lets say in **Alpha** stage, im not going to support database updates.   
**What does it means?**  
The thing is, since its hard to memorize all the changes made to the core, and since im coding like 8 hours a day, im not going to create a separate file for each **DB** change i made (just for now, later ill switch to same scheme as **TrinityCore** uses for **World** updates). So its either full re-installation of **Database** or just dont try to download it.  

**What im trying to say is**
###Its not ready for 'production' yet!