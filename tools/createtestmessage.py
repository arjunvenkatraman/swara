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
	db.addCommentToChannel("9811142821","12345")
