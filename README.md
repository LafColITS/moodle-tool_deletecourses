Delete courses
==============

[![Build Status](https://api.travis-ci.org/LafColITS/moodle-tool_deletecourses.png)](https://api.travis-ci.org/LafColITS/moodle-tool_deletecourses)

This admin tool allows managers to delete all courses in a category (including subcategories if desired). The tool may optionally bypass the recycle bin for improved performance.

Requirements
------------
- Moodle 3.2 (build 2016120500 or later)

Installation
------------
Copy the deletecourses folder into your /admin/tool directory and visit your Admin Notification page to complete the installation.

Usage
-----
The tool a link to the category navigation block, "Delete all courses." The user will be taken to a page describing what will happen and requesting confirmation. On confirmation, Moodle will create an "[adhoc task](https://docs.moodle.org/dev/Task_API#Adhoc_tasks)" to delete all the courses in the background. This requires the cron be enabled.

If you're using Boost or a similar theme, you may need to access /course/index.php directly, navigate to the desired category, then click the edit cog at top right to reach the links.

Configuration
-------------
The tool has no options but does require, as mentioned above, that cron be running.

Acknowledgements
----------------
Eric Merrill at Oakland University suggested the locking functionality and the method for bypassing the recycle bin.

Author
------
Charles Fulton (fultonc@lafayette.edu)
