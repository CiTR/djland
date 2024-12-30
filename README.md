# DJLAND 2024 notes

## deployment (pre-fullhost migration)
### setup:
Add your machine's public SSH key to the moongrok server ~/djland/.ssh/authorized_keys directory.
In Bitbucket, set yourself up with read/write access to the repo there ( `git@bitbucket.org:CiTR/djland.git` )

Then set up the remotes as such:
`git remote add origin git@bitbucket.org:CiTR/djland.git`
`git remote add live djland@djland.citr.ca:~/djland`

Use the branch `dev` to work from Bitbucket
`git checkout dev; git pull origin`

To deploy, push like so: `git push live`

Then ssh onto the djland server:
`ssh djland@djland.citr.ca`
`cd djland`
`git branch -v` (ensure the branch `master-live`) is checked out, verify the checked out commit is included in dev's commit history
`git merge dev`

## dev environment setup

- create a db and user in your mysql environment (eg. MAMP)
- import from seed sql file
- copy `config.php.sample` to `config.php` and edit db credentials
- copy `app/api2/.env.sample` to `app/api2/.env` and edit db credentials
- install and set up Composer (https://getcomposer.org/doc/00-intro.md)
- run `composer install` in the `app/api2` directory
- run `php artisan key:generate`

- for additional notes, see https://bitbucket.org/CiTR/technical-transition/src/master/trance.md











# DJLAND old readme content

![Build Status](https://travis-ci.org/CiTR/djland.svg?branch=master)

A free and open source web application built for community radio.

Optionally, DJLAND can also integrate with SAM Broadcaster (https://spacial.com/sam-broadcaster) for additional features, such as AD Scheduling and SAM play history access.

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
