#!/usr/bin/python
import os,sys,time
sys.path.append("/opt/swara/libs")
from database import *
from utilities import *
import datetime, xmlrpclib
import ConfigParser
config=ConfigParser.ConfigParser()

db = Database()	 

def getLastPushedPostID():
	post=os.popen("cat lastpushedpost").read().strip()
	return post

def wp_post(post):
    wp_url = "http://localhost/wp/xmlrpc.php"
    wp_username = "admin"
    wp_password = "<pass>"  
    wp_blogid = ""
    status_draft = 0
    status_published = 1
    server = xmlrpclib.ServerProxy(wp_url)
    title = db.getTitleforPost('12345',post) 
    content = db.getContentforPost('12345',post) + "\n<pre>[mp3j track='"+str(post)+".mp3']"
    #content = "<pre>[mp3-jplayer tracks='7355.mp3, url, FEED:http://10.16.16.14/swara/index.php?id=7355']"
    time=db.getPostedTime('12345',post)
    date_created = xmlrpclib.DateTime(time)
    categories = ["somecategory"]
    tags = ["sometag", "othertag"]
    data = {'title': title, 'description': content, 'dateCreated': date_created, 'categories': categories, 'mt_keywords': tags}
    post_id = server.metaWeblog.newPost(wp_blogid, wp_username, wp_password, data, status_published)
    return post_id

if __name__=="__main__":
	#Create Database object
	postid=getLastPushedPostID() 
	posts=db.getUnpushedPostsInChannel(12345,postid)
	if len(posts) == 0:
		print "No unpushed posts"
		exit()
	for post in posts:
		try:
			post_id=wp_post(post)
                        debugPrint("Posted Wordpress Post ID: " + str(post_id))
		except:
			print "Could not post to Wordpress %s" %post
			continue
	debugPrint("Final post = " + str(post))
        os.system("echo %s > lastpushedpost" %(str(post)))





