##Readme

For local testing, change the url for the data source in cron.php to use the testdata file and change values as required in the testdata file.

The values to change are: 

* (awayGoals) and (homeGoals) will update respective team goals.

* (start) is the time the game starts in UNIX format. The script will close ticket purchases for a match 48 hours before this value and show goals from 105 minutes after this value. 

Remember to run the cron after changes to view the updates. 
