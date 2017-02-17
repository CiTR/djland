# DJLAND

![Build Status](https://travis-ci.org/CiTR/djland.svg?branch=master)]

This branch consists of work done as part of a UBC Capstone Project, 2016/2017.

A free and open source web application built for community radio.

Optionally, DJLAND can also integrate with SAM Broadcaster (http://spacial.com/sam-broadcaster) for additional features, such as AD Scheduling and SAM play history access.

The installation process is very similar to Wordpress - just specify your server's database credentials and everything else is built automatically.

Requires a server with PHP and MySQL.

DJLAND Features...

Playsheets:
DJLAND features a flexible and easy to use playsheet tool that has been designed primarily for humans, not robots.  As an optional feature, it can connect to an instance of SAM Broadcaster and import plays from its history.  These plays can be edited and re-ordered amongst plays from physical media that have been manually entered.  You can also bulk-add a time period where only SAM was DJing.
DJland playsheets have all the required fields for a community radio station licensed in Canada, and it displays percentage-levels of compliance that are updated live while new entries are added to the playsheet.  DJs can save a draft if they are not finished in the on-air booth and resume at a later time by accessing the website from home or the station lounge.

Chart View:
A seperate view built for the Music Director automatically collects plays according to the latest Charting Week.

Music Catalog:
DJLand has a searchable Music Database for managing physical CDs, at the release level.
Can search and organize by Canadian Content, Local Content, Playlist, Female Content, and Compilation, as well as Media Format.

Membership Management:
Keep track of station membership and search by volunteer interest, department, and other fields.

Show Management:
Maintain show and schedule info in DJLand to enable automatically pre-populating playsheets during the show air-time.

Ad Scheduling and tracking:
Ad scheduler that allows a staff member to select ads for individual shows.  A show's Ad Schedule is loaded into a playsheet while the show info is populated.  If SAM integration is enabled, DJLand can automatically import a list of Ads based on a SAM category.  Currently, this only works if SAM integration is enabled.

CRTC / SOCAN report generation:
Pre-formatted reporting including a concise compliance summary for the CRTC and/or SOCAN.
SOCAN periods can be set to automatically add Composer and time fields to Playsheets.

Podcasting:
Ties in with archiver software to create podcasts for each playsheet made at CiTR. Provides XMLs for podcasting to iTunes via feedburner, or directly from the XML. Please note that the repository for the archiver does not currently have set-up instructions, as we are working on re-creating the environment.



INSTALLATION STEPS
The setup files are not currently up to date. They will be updated to reflect changes soon. In the mean time, please contact Evan (technicalmanager@citr.ca) for assistance with setting up a DJLand instance.

The data structures found in the setup folder are up to date however, so if you wish to set up your database, and ask for further steps feel free to do so :)

Developed and maintained by CiTR - www.citr.ca
