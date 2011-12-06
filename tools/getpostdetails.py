#!/usr/bin/python
# -*- coding: utf-8 -*-
#Needs Mutagen to be installed : easy_install mutagen
#Menu system
import sys,os
sys.path.append("/opt/swara/libs")
from utilities import *
from database import *
from swara import *
from mutagen.mp3 import MP3
import time;
if __name__=="__main__":
	db=Database()
	timestamp=os.popen("date +%H:%M-%Y%m%d").read().strip()
	#print "Cleaning empty files at %s \n" %timestamp
	posttest=Post()
	print posttest.test
	postid=sys.argv[1]
	post = db.getPostDetails('12345',postid)
	for data in post:
		print data
