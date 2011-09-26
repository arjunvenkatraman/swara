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
	timestamp=os.popen("date +%H:%M-%Y%m%d").read().strip()
	#pr "Cleaning empty files at %s \n" %timestamp
	postid=sys.argv[1]
	post = db.getPostDetails('12345',postid)
	for data in post:
		print "******************************************\nid: " + str(data[0]) + "\nskip_count: " +str(data[1])+  "\nposted: " +str(data[2])+ "\nauthor_id: "+str(data[3])+"\nmessage_input"+str(data[4])+"\nstatus: "+str(data[5])+ "\nedited: "+str(data[6])+"\nstation: "+str(data[7])+"\naudio_file: "+str(data[8])+"\ntitle: "+str(data[9])+"\nfilelocal: "+str(data[10])+"\naudio_type: "+str(data[11])+"\naudio_length: "+str(data[12])+"\naudio_size: "+str(data[13])+"\nmessage_html: "+str(data[14])+"\ncomment_on: "+str(data[15])+"\ncomment_size: "+str(data[16])+"\ncategory1_id:"+str(data[17])+"\ncategory2_id: "+str(data[18])+"\ncategory3_id: "+str(data[19])+"\ncategory4_id: "+str(data[20])+"\ntag: "+str(data[21])+"\ncountweb: "+str(data[22])+"\ncountfla: "+str(data[23])+"\ncountpod: "+str(data[24])+"\ncountall: "+str(data[25])+"\nvideowidth: "+str(data[26])+ "\nvideoheight: "+str(data[27])+"\nexplicit: "+str(data[27])+"\nsticky: "+str(data[28])+"\nuser: "+str(data[29])+"\ntime: "+str(data[30])+"\nsms_summary: "+str(data[31])+"\n******************************************"
