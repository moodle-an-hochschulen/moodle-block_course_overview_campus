moodle-block_course_overview_campus
===================================

Moodle block which provides all functionality of block_course_overview, provides additional filters (course category, course term and course teachers) to be used on university campuses as well as the possibility to hide courses and course news from the course list.


Requirements
------------

This plugin requires Moodle 3.1+


Changes
-------

* 2016-11-07 - Remove a debug message about missing name fields in the DB query if teacher names are configured to be displayed according to the fullnamedisplay setting
* 2016-07-19 - Check compatibility for Moodle 3.1, no functionality change
* 2016-06-14 - New Feature: Hide suspended teachers
* 2016-04-05 - Split the existing long settings page into multiple settings pages
* 2016-04-01 - Add feature to show top level category name in second row; rename existing feature to show parent category name
* 2016-04-01 - Add filter for top level category; rename existing category filter to parent category filter
* 2016-03-02 - Fix missing data in second row when corresponding filters are not activated; Credits to Dimitri Vorona
* 2016-02-10 - Change plugin version and release scheme to the scheme promoted by moodle.org, no functionality change
* 2016-01-01 - Add support for Shifter in YUI files, fix several JSLint errors
* 2016-01-01 - Check compatibility for Moodle 3.0, no functionality change
* 2015-09-29 - Output introduction string in course news like it's done in block_course_overview
* 2015-08-21 - Change My Moodle to Dashboard in language pack
* 2015-08-18 - Check compatibility for Moodle 2.9, no functionality change
* 2015-03-21 - Bugfix: Block couldn't be placed on MyMoodle in some circumstances
* 2015-03-20 - New Feature: Add a setting to control if the block should, when looking for teachers with the specified teacher roles, include teachers who have their role assigned in parent contexts (course category or system level)
* 2015-02-22 - Bugfix: Teacher filter showed teachers twice or even multiple times, Thanks to Mario Wehr
* 2015-02-22 - Bugfix: Term filter might have listed terms twice; Thanks to Michael Veit
* 2015-01-29 - Check compatibility for Moodle 2.8, no functionality change
* 2014-10-20 - Bugfix: There were problems with the term filter and courses which start on the term start day and / or term starting on january 1st
* 2014-10-20 - Add multilanguage support to noteachertext string
* 2014-08-29 - Update README file
* 2014-08-22 - Added setting to hide second row in course list on mobile phones to save space
* 2014-08-22 - Bootstrapbase makes h3 headings uppercase, this is not desired for this block and was overwritten in styles_bootstrapbase.css
* 2014-08-22 - Changed HTML code to leverage Bootstrap based themes, Drop support for Non-Bootstrap based themes
* 2014-08-22 - Changed HTML code for hide course management box - please check your theme, if you have styled the block in a custom way
* 2014-08-19 - Added setting to disable hiding of courses completely
* 2014-08-19 - Added setting to control the styling of the teacher's name in the second row
* 2014-06-30 - Check compatibility for Moodle 2.7, no functionality change
* 2014-02-18 - Bugfix: Second row didn't show the configured string for "timeless courses"; Credits to Sebastian Becker
* 2014-01-31 - Improve width of filters if less than all three filters are enabled
* 2014-01-31 - Added setting to skip activities when collecting and displaying course news
* 2014-01-31 - Added setting to disable course news completely and to hide course news by default
* 2014-01-31 - Added setting to define a placeholder text for course list entries if the block is configures to display teacher names but no teacher is enrolled in the course
* 2014-01-31 - Bugfix: Second row in course list was empty if the block was configured to show only teacher names in second row
* 2014-01-31 - Check compatibility for Moodle 2.6, no functionality change
* 2013-10-29 - Bugfix: block_course_overview_campus variable names interfered with other plugin's variables
* 2013-09-04 - Bugfix: Long course lists were incomplete. Sorry for the inconvenience!
* 2013-09-03 - Added ability to fine-tune the course name and meta info which will be displayed in the course overview list entries. Please revise your settings after updating the plugin
* 2013-09-03 - Added ability to fine-tune the term names which will be displayed in the term filter dropdown. Please revise your settings after updating the plugin, especially if you are running the term filter in Academic year mode
* 2013-07-30 - Transfer Github repository from github.com/abias/... to github.com/moodleuulm/...; Please update your Git paths if necessary
* 2013-07-30 - Check compatibility for Moodle 2.5, no functionality change
* 2013-06-18 - Bugfix: Fix problem with new ability to prioritize courses in which I teach. Sorry for the inconvenience
* 2013-06-18 - Re-sorted block settings page
* 2013-06-18 - Added ability to prioritize courses in which I teach in the course overview list
* 2013-06-18 - Added ability to merge homonymous categories into one category when using the category filter
* 2013-06-18 - Added ability to set the title of the block instead of using title from block_course_overview
* 2013-06-18 - Added ability to define teacher roles in block settings instead of relying on Moodle core coursecontact setting
* 2013-06-18 - Bugfix: When show teacher names setting was enabled, but teacher filter was diabled, the teacher name's list was not populated correctly
* 2013-06-12 - When managing hidden courses, now all courses are shown regardless if of the user's filter settings
* 2013-06-12 - Bugfix: Setting page should check if the configured term dates make sense and show a warning information if not. This check didn't work up to now
* 2013-04-23 - Add support for timeless courses
* 2013-03-18 - Code cleanup according to moodle codechecker
* 2013-03-06 - Bugfix: Block failed to work when wwwroot contained a subdirectory, kudos to Michael Wuttke
* 2013-03-05 - Small code change, now PHP doesn't need to be compiled with --enable-calendar option, kudos to Carsten Biemann
* 2013-02-22 - German language has been integrated into AMOS and was removed from this plugin. Please update your language packs with http://YOURMOODLEURL/admin/tool/langimport/index.php after installing this plugin version
* 2013-02-18 - Check compatibility for Moodle 2.4, add module icons to course news, fix language string names to comply with language string name convention
* 2013-01-18 - Bugfix: Block didn't read configuration from config_plugins database table properly
* 2012-12-21 - Block now uses config_plugins database table instead of config table. You will have to set all block settings again, sorry about that!
* 2012-12-21 - Small CSS improvement
* 2012-12-18 - Code cleanup
* 2012-12-18 - New feature: Short teachers' names in course list
* 2012-12-18 - New feature: Support multilang strings in term names and filter display names
* 2012-12-17 - Initial version


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

To make use of the advanced features of the block, please visit Plugins -> Blocks -> Course overview on campus.

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
If the course's category path is Category A -> Category B -> Category C -> Course, the filter will contain an extry with Category C.


### 2. Top level category filter

The top level filter is filled with the top level category of each of the user's courses.

Example:
If the course's category path is Category A -> Category B -> Category C -> Course, the filter will contain an extry with Category A.

### 3. Teacher filter

As described in the "Usage & Settings" section of this file, you should configure the teacher roles for block_course_overview_campus according to your campus needs. After that, block_course_overview_campus takes each course member with one of the configured roles. These teachers are filled into the teacher filter.

### 4. Term filter

As described in the "Usage & Settings" section of this file, you should configure block_course_overview_campus according to your campus course of the year. After that, block_course_overview_campus maps each course to a term by looking at the course's start date. This term is filled into the term filter.


Disregarded Moodle Features
---------------------------

During the development of Moodle, there have been added several features added to the moodle core block_course_overview and moodle core which would conflict with block_course_overview_campus functionality. It has been decided to disregard the following Moodle features for this block:

* In block_course_overview in Moodle 2.4+, a user is able to sort his course list by drag and drop. We decided to not adopt this feature for block_course_overview_campus because we think this would conflict with the filtering / hiding feature and confuse users. In block_course_overview_campus, the course list remains sorted by full course name.
* In block_course_overview in Moodle 2.4+, a user is able to limit the length of his course list with a block setting. We decided to not adopt this feature for block_course_overview_campus because we think this would conflict with the filtering / hiding feature and confuse users. In block_course_overview_campus, the course list always shows all courses which have passed the selected course filters.
* In block_course_overview in Moodle 2.4+, the administrator can configure the block to show Metacourse children. We decided to not adopt this feature for block_course_overview_campus because we have no need for this. If you need this feature, please let me know on https://github.com/moodleuulm/moodle-block_course_overview_campus/issues
* In block_course_overview in Moodle 2.4+, the administrator can configure the block to show a welcome message. We decided to not adopt this feature for block_course_overview_campus because we have no need for this. If you need this feature, please let me know on https://github.com/moodleuulm/moodle-block_course_overview_campus/issues
* In block_course_overview in Moodle 2.4+, course news are grouped by modules and each module can be collapsed / expanded. We decided to stick with the behaviour of block_course_overview_campus to collapse / expand course news as a whole, but we added some nice icons for each module to the course news list.
* In Moodle core since Moodle 2.2+, there is a setting "courselistshortnames" which controls the displaying of course names. This setting is also processed in block_course_overview. We decided to ignore this core setting and to stick to block_course_overview_campus's internal course display control settings.


MNet courses
------------

In contrast to the moodle core block_course_overview, this block doesn't support MNet courses and wasn't tested with MNet Moodle installations.


Themes
------

block_course_overview_campus should work with all Bootstrap based Moodle themes.
block_course_overview_campus provides a fallback for browsers with JavaScript disabled.


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
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send me a pull request on
github with modifications.


Copyright
---------

University of Ulm
kiz - Media Department
Team Web & Teaching Support
Alexander Bias


Credits
-------

This plugin is an enhanced version of Andrew James' block_course_overview_plus (https://moodle.org/plugins/view.php?plugin=block_course_overview_plus) which was enhanced to fit the needs of university campuses.
