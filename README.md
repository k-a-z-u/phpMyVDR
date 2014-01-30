phpMyVDR
========

# About
**phpMyVDR** is a PHP-based web-interface for the well known **VDR** project. But, why another VDR interface while VDR has its own OSD, and other projects already exist?
I mainly work on gentoo and use VDR on a dedicated server (without monitor) and watch TV solely via LAN. Other projects had huge dependency lists and apache2/php was already present on my server. Therefore I decided to work on a small PHP based web-interface.

The current version allows searching for shows, adding and modifying timers, show search results as RSS and some more features. While there is still a lot of work to do, also on the security aspect (searching for possible injections), the interface is small and easy to set up.


# What you need
* **A webserver** (like apache2)
* **a recent PHP version** with
 * **socket** (connection to VDR via SVDRP) and 
 * **sqlite3** (epg database) support.


# Setup
Unfortunately there is currently no guided setup process. But, you just need a **setup.php**, **setup.php.example** is already present and only requires minor modifications to ensure the web-interface can find VDRs **epg.data**.
Also the interface **should** inform about missing / wrongly configured parameters. If not, feel free to contact me so I can fix this issue ;)


# How does it work?
To provide fast EPG searching, phpMyVDR scans your **epg.data** and puts the entries into a sqlite database providing a much faster access. As PHP does (normally) not provide background services, the process of converting **epg.data** to this database must be triggered from time to time using the **EPG-Update** within the main menu. Maybe there will be workarounds like cronjobs or automatically triggered updates in the future ;)


# License
Apache License Version 2.0
http://apache.org/licenses/LICENSE-2.0.txt
