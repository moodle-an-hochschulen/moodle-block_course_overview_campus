moodle-block_course_overview_campus
===================================
Moodle block which provides all functionality of block_course_overview, provides additional filters (course category, course term and course teachers) to be used on university campuses as well as the possibility to hide courses and course news from the course list.


Requirements
============
This plugin requires Moodle 2.3+


Changes
=======
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

See http://docs.moodle.org/23/en/Installing_plugins for details on installing Moodle plugins


Placement
=========
block_course_overview_campus is used ideally as sticky block and placed on your frontpage (and mymoodle page, if your moodle instance uses this pagetype).

See http://docs.moodle.org/23/en/Sticky_blocks for details about sticky blocks


Usage & Settings
================
After installing block_course_overview_campus with its default settings and adding it to your frontpage, it behaves like block_course_overview from moodle core. Additionally, news from your courses can be folded and unfolded and courses can be hidden from the course list.

To make use of the advanced features of the block, please visit Plugins -> Blocks -> Course overview on campus.

There, you find three sections:

1. Filter activation
--------------------
By checking one of these items, you activate a filter which enables your users to filter their courses by category, by term or by teachers. As soon as the filter is activated and the setting is saved, the filter appears in the block view.

2. Term definition
------------------
To make meaningfully use of the term filter, you have to configure it according to your campus course of the year and campus terminology. First, select if your year is divided into one, two, three or four terms. Then, set the start days of each term. After that, set a label for each term according to your campus terminology (multilang strings are supported, see http://docs.moodle.org/23/en/Multi-language_content_filter for details). Finally, decide if a default term should be chosen if the user has not previously selected a term for filtering terms.

3. Appearance
-------------
In this section, you can enable the displaying of the course's short name and of the teachers' names in the course list. Additionally, you can change the display names for each filter which are shown in the block view (multilang strings are supported, see http://docs.moodle.org/23/en/Multi-language_content_filter for details).


Data sources
============

1. Category filter
------------------
The category filter is filled with the main category of each of the user's courses. 
Currently, there is no support for parent categories, grandparent categories and so on or any other category filtering. Please don't hesitate to suggest expedient improvements.

2. Teacher filter
-----------------
block_course_overview_campus gets the list of teacher roles from $CFG->coursecontact. With this Moodle core setting, you can define which roles (and thereby which teachers) are displayed in block_course_overview_campus's teacher filter.

3. Term filter
--------------
As described in the "Usage & Settings" section of this file, you should to configure block_course_overview_campus according to your campus course of the year. After that, block_course_overview_campus maps each course to a term by looking at the course's start date. This term is filled into the term filter.


MNet courses
============
In contrast to the moodle core block_course_overview, this block doesn't support MNet courses and wasn't tested with MNet Moodle installations.


Themes
======
block_course_overview_campus should work with all themes from moodle core.
block_course_overview_campus provides a fallback for browsers with JavaScript disabled.


Further information
===================
Report a bug or suggest an improvement: https://github.com/abias/moodle-block_course_overview_campus/issues


Copyright
=========
Alexander Bias, University of Ulm


Credits
=======
This plugin is an enhanced version of Andrew James' block_course_overview_plus (https://moodle.org/plugins/view.php?plugin=block_course_overview_plus) which was enhanced to fit the needs of university campuses.
