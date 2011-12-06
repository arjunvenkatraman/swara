#!/usr/bin/python
# -*- coding: utf-8 -*-
#Needs Mutagen to be installed : easy_install mutagen
#Menu system
import sys,os
sys.path.append("/opt/swara/libs")
from utilities import *
from database import *
import time;
if __name__=="__main__":
	db=Database()
	posts = db.getAllPostsInChannel('12345')
	for post in posts:
