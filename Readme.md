# FreedomCore (aka Freedom.Net)

[![Donate](https://static.freedomcore.ru/images/freedomcore/paypal-donate.jpg)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=apple%2ezhivolupov%40gmail%2ecom&lc=GB&item_name=FreedomCore&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)

FreedomCore is a replica of Battle.net website created by Blizzard.  
My desire is full compliance between FreedomCore and Battle.net.  
  
Because it is most likely to happen that the development of the project will be only on me, please do not expect for the instant correction of errors and bugs, as well as new features. Of course, I'll be correcting them and so on, but please understand me, I need time.

#Attention
If you plan to sell the project or are about to give it as your own development, just go to hell. The project has been free, is free and will remain free.   

I want to point out that despite the fact that the CSS and JS (80%) owned by Blizzard Entertainment, the rest of the code is the property of the developer (darki73 in this case).   
With a very strong desire from the side of the developer, you will be able to "find" a few new problems from legal point of view.

Respect the work of people, and follow the simple rules of decency.

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

#### Windows
If you are using Windows, then all you have to do is clone repo and point Apache to that folder.

#### Linux
```sh
$ git clone https://github.com/darki73/FreedomCore.git .
$ chmod 755 -R Cache
$ chmod 755 -R Uploads
```

After you've cloned repo and set permissions (Linux only), you need to access website using Address or IP you've given to it.

For Example
```
http://net.freedomcore.ru  
http://localhost
http://127.0.0.1
```   

Browser will be redirected to the installation page. Just fill in the data it asks you to fill in and you are good to go.

###Importing Database
- There is a **.7z** file in **Install/sql** folder which you need to **extract** and import to **Website database** in order to have icons, etc.

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