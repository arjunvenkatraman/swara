#!/usr/bin/python
# -*- coding: utf-8 -*-
#Needs Mutagen to be installed : easy_install mutagen
#Menu system
import sys,os
from utilities import *
from database import *
from mutagen.mp3 import MP3
import time;
if __name__=="__main__":
	db=Database()
	while True:
		timestamp=os.popen("date +%H:%M-%Y%m%d").read().strip()
		#print "Cleaning empty files at %s \n" %timestamp
		posts = db.getAllPostsInChannel('12345')
		for post in posts:
			line="/home/swara/audiowiki/web/sounds/web/%s.mp3" %str(post)	
			try:
				audio = MP3(line)
			except IOError:
				print "MP3 file not found for post %s deleting post" %line
				db.deletePost(post)
				pass
			#print line,audio.info.length
			if float(audio.info.length) < 20:
				filename=line
				#post = filename.split("sounds/web/")[0].split('.')[0]
				db.deletePost(post)
				with open("/var/log/swara.log", "a") as f:
					f.write("Deleted short post %s length was %s \n" %(post,audio.info.length))
				#print "Deleted short post %s length was %s \n" %(post,audio.info.length)
				#os.system("rm %s" %filename)
				os.system("mv %s /home/swara/audiowiki/web/sounds/web/trash" %filename)
		timestamp=os.popen("date +%H:%M-%Y%m%d").read().strip()
		with open("/var/log/swara.log", "a") as f:
			f.write("Cleaned empty files at %s \n" %timestamp)
		#print "Cleaned empty files at %s \n" %timestamp
		time.sleep(180)
