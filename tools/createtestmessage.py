#!/usr/bin/python
# -*- coding: utf-8 -*-
#Needs Mutagen to be installed : easy_install mutagen
#Menu system
import sys,os
sys.path.append("/opt/swara/libs")
from utilities import *
from database import *
from mutagen.mp3 import MP3
import time;
if __name__=="__main__":
	db=Database()
	auth=db.getAuthDetails(sys.argv[1])
	if auth==0:
		auth=db.addAuthor(sys.argv[1])
	db.addCommentToChannel(sys.argv[1],auth,"12345")
