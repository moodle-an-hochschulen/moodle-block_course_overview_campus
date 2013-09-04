moodle-block_course_overview_campus
===================================
Moodle block which provides all functionality of block_course_overview, provides additional filters (course category, course term and course teachers) to be used on university campuses as well as the possibility to hide courses and course news from the course list.


Requirements
============
This plugin requires Moodle 2.5+


Changes
=======
2013-09-04 - Bugfix: Long course lists were incomplete. Sorry for the inconvenience!
2013-09-03 - Added ability to fine-tune the course name and meta info which will be displayed in the course overview list entries. Please revise your settings after updating the plugin
2013-09-03 - Added ability to fine-tune the term names which will be displayed in the term filter dropdown. Please revise your settings after updating the plugin, especially if you are running the term filter in Academic year mode
2013-07-30 - Transfer Github repository from github.com/abias/... to github.com/moodleuulm/...; Please update your Git paths if necessary
2013-07-30 - Check compatibility for Moodle 2.5, no functionality change
2013-06-18 - Bugfix: Fix problem with new ability to prioritize courses in which I teach. Sorry for the inconvenience
2013-06-18 - Re-sorted block settings page
2013-06-18 - Added ability to prioritize courses in which I teach in the course overview list
2013-06-18 - Added ability to merge homonymous categories into one category when using the category filter
2013-06-18 - Added ability to set the title of the block instead of using title from block_course_overview
2013-06-18 - Added ability to define teacher roles in block settings instead of relying on Moodle core coursecontact setting
2013-06-18 - Bugfix: When show teacher names setting was enabled, but teacher filter was diabled, the teacher name's list was not populated correctly
2013-06-12 - When managing hidden courses, now all courses are shown regardless if of the user's filter settings
2013-06-12 - Bugfix: Setting page should check if the configured term dates make sense and show a warning information if not. This check didn't work up to now
2013-04-23 - Add support for timeless courses
2013-03-18 - Code cleanup according to moodle codechecker
2013-03-06 - Bugfix: Block failed to work when wwwroot contained a subdirectory, kudos to Michael Wuttke
2013-03-05 - Small code change, now PHP doesn't need to be compiled with --enable-calendar option, kudos to Carsten Biemann
2013-02-22 - German language has been integrated into AMOS and was removed from this plugin. Please update your language packs with http://YOURMOODLEURL/admin/tool/langimport/index.php after installing this plugin version
2013-02-18 - Check compatibility for Moodle 2.4, add module icons to course news, fix language string names to comply with language string name convention
2013-01-18 - Bugfix: Block didn't read configuration from config_plugins database table properly
2012-12-21 - Block now uses config_plugins database table instead of config table. You will have to set all block settings again, sorry about that!
2012-12-21 - Small CSS improvement
2012-12-18 - Code cleanup
2012-12-18 - New feature: Short teachers' names in course list
2012-12-18 - New feature: Support multilang strings in term names and filter display names
2012-12-17 - Initial version


Installation
============
Install the plugin like any other plugin to folder
/blocks/course_overview_campus

See http://docs.moodle.org/25/en/Installing_plugins for details on installing Moodle plugins


Placement
=========
block_course_overview_campus is used ideally as sticky block and placed on your frontpage (and mymoodle page, if your moodle instance uses this pagetype).

See http://docs.moodle.org/25/en/Block_settings#Making_a_block_sticky_throughout_the_whole_site for details about sticky blocks


Usage & Settings
================
After installing block_course_overview_campus with its default settings and adding it to your frontpage, it behaves like block_course_overview from moodle core. Additionally, news from your courses can be folded and unfolded and courses can be hidden from the course list.

To make use of the advanced features of the block, please visit Plugins -> Blocks -> Course overview on campus.

There, you find multiple sections:

1. Appearance
-------------
In this section, you can change the block's title which is shown in the block view (multilang strings are supported, see http://docs.moodle.org/25/en/Multi-language_content_filter for details).

2. Course overview list entries
-------------------------------
In this section, you can configure if the course's full name or short name should be displayed in the course overview list entries. Additionally, you can enable the displaying of some meta data in a second row of the course overview list entry.

3. Course overview list order
-----------------------------
In this section, you can define if courses in which the user has a teacher role are listed first in the course overview list.

4. Teacher roles
----------------
In this section, you can define which roles in a course will be displayed besides the course's name as teacher and get listed in the teacher filter.

5. Category filter: Filter activation
-------------------------------------
By checking this item, you activate a filter which enables your users to filter their courses by category. As soon as the filter is activated and the setting is saved, the filter appears in the block view.
Additionally, you can change the display name for this filter which is shown in the block view (multilang strings are supported, see http://docs.moodle.org/25/en/Multi-language_content_filter for details).

5.1 Category filter: Merge homonymous categories
------------------------------------------------
If there are multiple categories with different parent categories, but with the same name, the category filter will be filled with multiple categories with the same name by default. This can be confusing to the user. If you want to merge all homonymous categories into one category when using the category filter, activate this setting

6. Teacher filter: Filter activation
------------------------------------
By checking this item, you activate a filter which enables your users to filter their courses by teacher. As soon as the filter is activated and the setting is saved, the filter appears in the block view.
Additionally, you can change the display name for this filter which is shown in the block view (multilang strings are supported, see http://docs.moodle.org/25/en/Multi-language_content_filter for details).

7. Term filter: Filter activation
---------------------------------
By checking this item, you activate a filter which enables your users to filter their courses by term. As soon as the filter is activated and the setting is saved, the filter appears in the block view.
Additionally, you can change the display name for this filter which is shown in the block view (multilang strings are supported, see http://docs.moodle.org/25/en/Multi-language_content_filter for details).

7.1. Term filter: Term definition
---------------------------------
To make meaningfully use of the term filter, you have to configure it according to your campus course of the year. First, select if your year is divided into one, two, three or four terms. Then, set the start days of each term (Please make sure that the configured start dates make sense, i.e. that term 2 starts after term 1 and so on).

7.2. Term filter: Term names
----------------------------
To make meaningfully use of the term filter, you have to configure it according to your campus terminology. In this section, you can set a label for each term according to your campus terminology (multilang strings are supported, see http://docs.moodle.org/25/en/Multi-language_content_filter for details).
Additionally, you can fine-tune the displaying of the term names which will be displayed in the term filter dropdown.

7.3 Term filter: Term behaviour
-------------------------------
Here, you are able to let block_course_overview_campus choose a default term if the user has not previously selected a term for filtering terms.

7.4 Term filter: Timeless courses
---------------------------------
Here, you can enable support for "timeless courses". Timeless courses will be presented in the term filter as if they are not associated to a specific term. This is achieved by leveraging the course's start year field. After enabling timeless courses, you have to define a course start year threshold. Every course with a start year before (and not equal to) this year will be presentes as timeless course in the term filter.
You are also able to set a label for timeless courses for the term filter (multilang strings are supported, see http://docs.moodle.org/25/en/Multi-language_content_filter for details).


Data sources
============

1. Category filter
------------------
The category filter is filled with the main category of each of the user's courses.
Currently, there is no support for parent categories, grandparent categories and so on or any other category filtering. Please don't hesitate to suggest expedient improvements.

2. Teacher filter
-----------------
As described in the "Usage & Settings" section of this file, you should configure the teacher roles for block_course_overview_campus according to your campus needs. After that, block_course_overview_campus takes each course member with one of the configured roles. These teachers are filled into the teacher filter.

3. Term filter
--------------
As described in the "Usage & Settings" section of this file, you should configure block_course_overview_campus according to your campus course of the year. After that, block_course_overview_campus maps each course to a term by looking at the course's start date. This term is filled into the term filter.


Moodle 2.4+ Features
====================
In contrast to previous Moodle versions, Moodle 2.4 added some features to the moodle core block_course_overview:
- In block_course_overview in Moodle 2.4+, a user is able to sort his course list by drag and drop. I decided to not adopt this feature for block_course_overview_campus because I think this would be conflict with the filtering / hiding feature and confuse users. In block_course_overview_campus, the course list remains sorted by full course name.
- In block_course_overview in Moodle 2.4+, the administrator can configure the block to show Metacourse children. I decided to not adopt this feature for block_course_overview_campus because we have no need for this. If you need this feature, please let me know on https://github.com/moodleuulm/moodle-block_course_overview_campus/issues
- In block_course_overview in Moodle 2.4+, the administrator can configure the block to show a welcome message. I decided to not adopt this feature for block_course_overview_campus because we have no need for this. If you need this feature, please let me know on https://github.com/moodleuulm/moodle-block_course_overview_campus/issues
- In block_course_overview in Moodle 2.4+, course news are grouped by modules and each module can be collapsed / expanded. I decided to stick with the behaviour of block_course_overview_campus to collapse / expand course news as a whole, but I added some nice icons for each module to the course news list.


MNet courses
============
In contrast to the moodle core block_course_overview, this block doesn't support MNet courses and wasn't tested with MNet Moodle installations.


Themes
======
block_course_overview_campus should work with all themes from moodle core.
block_course_overview_campus provides a fallback for browsers with JavaScript disabled.


Further information
===================
Report a bug or suggest an improvement: https://github.com/moodleuulm/moodle-block_course_overview_campus/issues


Moodle release support
======================
Due to limited ressources, block_course_overview_campus is only maintained for the most recent major release of Moodle. However, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until I can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that block_course_overview_campus still works with a new major relase - please let me know on https://github.com/moodleuulm/moodle-block_course_overview_campus/issues


Right-to-left support
=====================
This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send me a pull request on
github with modifications.


Copyright
=========
Alexander Bias, University of Ulm


Credits
=======
This plugin is an enhanced version of Andrew James' block_course_overview_plus (https://moodle.org/plugins/view.php?plugin=block_course_overview_plus) which was enhanced to fit the needs of university campuses.
