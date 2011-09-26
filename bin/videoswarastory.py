#!/usr/bin/python

# Requires tweepy,simplejson and oauth0
# further requires a config file (tweetrswara.conf) with the access token for the swara user

import os,sys,time
from database import *
from utilities import *
import ConfigParser
import oauth2 as oauth
import os,sys


db = Database()    

def getLastPushedPostID():
	post=os.popen("cat lastvideodpost").read().strip()
	return post


if __name__=="__main__":
	#Create Database object
   	postid=getLastPushedPostID() 
	print postid
	posts=db.getUnpushedPostsInChannel(12345,postid)
	if len(posts) == 0:
		print "No unpushed posts"
		exit()
	for post in posts:
		os.system("/home/swara/audiowiki/mp32avi.sh /home/swara/audiowiki/SwaraCoverLarge.jpg /home/swara/audiowiki/web/sounds/web/%s.mp3 /home/swara/audiowiki/web/videos/%s.avi" %(str(post),str(post)))
	print "Final post = " + str(post)
	
	os.system("echo %s > lastvideodpost" %(str(post)))
