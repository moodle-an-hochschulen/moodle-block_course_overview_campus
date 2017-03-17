moodle-block_course_overview_campus
===================================

Moodle block which provides all functionality of block_course_overview, provides additional filters (course category, course term and course teachers) to be used on university campuses as well as the possibility to hide courses and course news from the course list.


Requirements
------------

This plugin requires Moodle 3.2+


Installation
------------

Install the plugin like any other plugin to folder
/blocks/course_overview_campus

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins


Placement
---------

block_course_overview_campus is used ideally as sticky block and placed on your frontpage (and / or Dashboard page).

See http://docs.moodle.org/en/Block_settings#Making_a_block_sticky_throughout_the_whole_site for details about sticky blocks


Usage & Settings
----------------

After installing block_course_overview_campus with its default settings and adding it to your frontpage, it behaves like block_course_overview from moodle core. Additionally, news from your courses can be folded and unfolded and courses can be hidden from the course list.

To make use of the advanced features of the block, please visit Site administration -> Plugins -> Blocks -> Course overview on campus.

There, you find multiple settings pages:

### 1. General

On this settings page, you can change the block's title which is shown in the block view (multilang strings are supported, see http://docs.moodle.org/en/Multi-language_content_filter for details).

### 2. Course overview list

On this settings page, you can change the appearance of the course overview list, especially if the course's full name or short name should be displayed in the course overview list entries. Additionally, you can enable and style the displaying of some meta data in a second row of the course overview list entry and you can define if courses in which the user has a teacher role are listed first in the course overview list.

### 3. Hide courses

On this settings page, you can enable (default) or disable the system for hiding courses from the course overview list.

### 4. Course news

On this settings page, you can configure if the course list should also show course news. Additionally, you can configure which and how course news are displayed.

### 5. Teacher roles

On this settings page, you can define which roles in a course will be displayed besides the course's name as teacher and get listed in the teacher filter.

### 6. Parent category filter

On this settings page, you can activate and configure a filter which enables your users to filter their courses by parent category. As soon as the filter is activated and the setting is saved, the filter appears in the block view.

### 7. Top level category filter

On this settings page, you can activate and configure a filter which enables your users to filter their courses by top level category. As soon as the filter is activated and the setting is saved, the filter appears in the block view.

### 8. Teacher filter

On this settings page, you can activate and configure a filter which enables your users to filter their courses by teacher. As soon as the filter is activated and the setting is saved, the filter appears in the block view.

### 9. Term filter

On this settings page, you can activate and configure a filter which enables your users to filter their courses by term. As soon as the filter is activated and the setting is saved, the filter appears in the block view.


Data sources
------------

### 1. Parent category filter

The parent category filter is filled with the main category of each of the user's courses.

Example:
If the course's category path is Category A -> Category B -> Category C -> Course, the filter will contain an entry with Category C.


### 2. Top level category filter

The top level filter is filled with the top level category of each of the user's courses.

Example:
If the course's category path is Category A -> Category B -> Category C -> Course, the filter will contain an entry with Category A.

### 3. Teacher filter

As described in the "Usage & Settings" section of this file, you should configure the teacher roles for block_course_overview_campus according to your campus needs. After that, block_course_overview_campus takes each course member with one of the configured roles. These teachers are filled into the teacher filter.

### 4. Term filter

As described in the "Usage & Settings" section of this file, you should configure block_course_overview_campus according to your campus course of the year. After that, block_course_overview_campus maps each course to a term by looking at the course's start date. This term is filled into the term filter.


Disregarded Moodle Features
---------------------------

During the development of Moodle, there have been added several features added to the moodle core block_course_overview and moodle core which would conflict with block_course_overview_campus functionality. It has been decided to disregard the following Moodle features for this block:

* In block_course_overview in Moodle 2.4+, a user is able to sort his course list by drag and drop. We decided to not adopt this feature for block_course_overview_campus because we think this would conflict with the filtering / hiding feature and confuse users. In block_course_overview_campus, the course list remains sorted by full course name.
* In block_course_overview in Moodle 2.4+, a user is able to limit the length of his course list with a block setting. We decided to not adopt this feature for block_course_overview_campus because we think this would conflict with the filtering / hiding feature and confuse users. In block_course_overview_campus, the course list always shows all courses which have passed the selected course filters.
* In block_course_overview in Moodle 2.4+, the administrator can configure the block to show Metacourse children. We decided to not adopt this feature for block_course_overview_campus because we have no need for this. If you need this feature, please let us know on https://github.com/moodleuulm/moodle-block_course_overview_campus/issues
* In block_course_overview in Moodle 2.4+, the administrator can configure the block to show a welcome message. We decided to not adopt this feature for block_course_overview_campus because we have no need for this. If you need this feature, please let us know on https://github.com/moodleuulm/moodle-block_course_overview_campus/issues
* In block_course_overview in Moodle 2.4+, course news are grouped by modules and each module can be collapsed / expanded. We decided to stick with the behaviour of block_course_overview_campus to collapse / expand course news as a whole, but we added some nice icons for each module to the course news list.
* In Moodle core since Moodle 2.2+, there is a setting "courselistshortnames" which controls the displaying of course names. This setting is also processed in block_course_overview. We decided to ignore this core setting and to stick to block_course_overview_campus's internal course display control settings.


MNet courses
------------

In contrast to the moodle core block_course_overview, this block doesn't support MNet courses and wasn't tested with MNet Moodle installations.


Themes
------

block_course_overview_campus should work with all Bootstrap based Moodle themes.
block_course_overview_campus provides a fallback for browsers with JavaScript disabled.


Companion plugin local_boostcoc
-------------------------------

Since the release of Moodle 3.2, Moodle core ships with a shiny new theme called "Boost". While Boost does many things right and better than the legacy theme Clean, it also has some fixed behaviours which don't make sense for all Moodle installations. One of these behaviours is the fact that the mycourses list in the nav drawer (the menu which appears when you click on the hamburger menu button) is non-collapsible, always contains all of my courses and can hardly be configured by administrators.

We have created local_boostcoc as a companion plugin to block_course_overview_campus which does its best to add support for filtering and hiding courses to the mycourses list in the nav drawer. local_boostcoc is published on http://moodle.org/plugins/view/local_boostcoc and on https://github.com/moodleuulm/moodle-local_boostcoc.


Further information
-------------------

block_course_overview_campus is found in the Moodle Plugins repository: http://moodle.org/plugins/view/block_course_overview_campus

Report a bug or suggest an improvement: https://github.com/moodleuulm/moodle-block_course_overview_campus/issues


Moodle release support
----------------------

Due to limited resources, block_course_overview_campus is only maintained for the most recent major release of Moodle. However, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until we can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that block_course_overview_campus still works with a new major relase - please let us know on https://github.com/moodleuulm/moodle-block_course_overview_campus/issues


Right-to-left support
---------------------

This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send us a pull request on
github with modifications.


PHP7 Support
------------

Since Moodle 3.0, Moodle core basically supports PHP7.
Please note that PHP7 support is on our roadmap for this plugin, but it has not yet been thoroughly tested for PHP7 support and we are still running it in production on PHP5.
If you encounter any success or failure with this plugin and PHP7, please let us know.


Copyright
---------

Ulm University
kiz - Media Department
Team Web & Teaching Support
Alexander Bias


Credits
-------

This plugin is an enhanced version of Andrew James' block_course_overview_plus (https://moodle.org/plugins/view.php?plugin=block_course_overview_plus) which was enhanced to fit the needs of university campuses.
