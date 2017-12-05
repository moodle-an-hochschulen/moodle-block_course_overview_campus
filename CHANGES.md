moodle-block_course_overview_campus
===================================

Changes
-------

### Unreleased

* 2017-12-05 - Added Workaround to travis.yml for fixing Behat tests with TravisCI.
* 2017-11-08 - Updated travis.yml to use newer node version for fixing TravisCI error.

### v3.2-r6

* 2017-06-16 - Bugfix: Prevent debug notice when there are no modules supporting the print_overview() function
* 2017-05-29 - Add Travis CI support

### v3.2-r5

* 2017-05-05 - Improve README.md

### v3.2-r4

* 2017-03-29 - Tighten parameter filtering for user preferences saved by block_course_overview_campus
* 2017-03-16 - Bugfix: Eliminate debug message about duplicate teacher role entries - Credits to Davo Smith
* 2017-03-10 - Don't show course news when hidden courses management is active
* 2017-03-10 - Bugfix: The hidden courses management box was partly broken after the styling changes in v3.2-r3
* 2017-03-10 - Bugfix: The fallback for browsers with JavaScript disabled was broken after the styling changes in v3.2-r3
* 2017-03-10 - Restructure code in several areas, especially to support our companion plugin local_boostcoc; No functionality change

### v3.2-r3

* 2017-03-04 - Change the styling of the block even more to Bootstrap 4

### v3.2-r2

* 2017-01-27 - Bugfix: Set filter correctly after using the browser's back functionality - Credits to Davo Smith

### v3.2-r1

* 2017-01-17 - Bugfix: Top level category filter did not show lower-level courses on first page load
* 2017-01-16 - Adapt course list appearance to Bootstrap 4 (used by theme_boost)
* 2017-01-16 - Check compatibility for Moodle 3.2, no functionality change
* 2017-01-16 - Convert YUI to jQuery + AMD - Credits to Davo Smith
* 2017-01-12 - Move Changelog from README.md to CHANGES.md

### v3.1-r2

* 2016-11-07 - Remove a debug message about missing name fields in the DB query if teacher names are configured to be displayed according to the fullnamedisplay setting

### v3.1-r1

* 2016-07-19 - Check compatibility for Moodle 3.1, no functionality change

### Changes before v3.1

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
