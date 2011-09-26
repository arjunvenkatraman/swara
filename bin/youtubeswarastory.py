#!/usr/bin/python
#Requires gdata and elementtree
# Requires tweepy,simplejson and oauth0
# further requires a config file (tweetrswara.conf) with the access token for the swara user

import os,sys,time
sys.path.append("/opt/swara/libs")
from database import *
from utilities import *
import ConfigParser
import tweepy
import oauth2 as oauth
import os,sys
import gdata.docs.service
import gdata.youtube
import gdata.youtube.service

config=ConfigParser.ConfigParser()
config.read("/home/swara/audiowiki/twittercredsswara.conf")

# Create a client class which will make HTTP requests with Google Docs server.
yt_service = gdata.youtube.service.YouTubeService()

# The YouTube API does not currently support HTTPS/SSL access.
yt_service.ssl = False
yt_service.developer_key = "AI39si5pjJmhiUXuwBzzIaXhx39O3USda1v40n7QPkHyw51jBsQLVs9qSD1Ilh9U2-Ny3466flm4lDDA2lpGxhqU1FCy1a7fsw"
yt_service.email = 'cgnetswarachannel@gmail.com'
yt_service.password = 'kissmyass3ce'
yt_service.ProgrammaticLogin()








OAUTH_TOKEN = config.get("Authentication","oauth_token")
OAUTH_TOKEN_SECRET = config.get("Authentication","oauth_token_secret")
CONSUMER_KEY = config.get("Consumer","consumer_key")
CONSUMER_SECRET = config.get("Consumer","consumer_secret")

#Create objects
auth=tweepy.OAuthHandler(CONSUMER_KEY,CONSUMER_SECRET)
auth.set_access_token(OAUTH_TOKEN,OAUTH_TOKEN_SECRET)

db = Database()    
api = tweepy.API(auth)

def getLastPushedPostID():
	post=os.popen("cat lastpushedpost").read().strip()
	return post

def youtubetweet(post,url, title):
	#url="http://cgnetswara.org/index.php?id="+str(post)
	print title + " " + url
	status=title +" "+ url
	api.update_status(status)
	#debugPrint("Tweeted post %s" %post)

if __name__=="__main__":
	list  = os.popen("ls -A /home/swara/audiowiki/web/videos/*.avi").read().strip('').split('\n')
	#print list
	for post in list:
		postid = post.split('/')[6].split('.')[0]
		if postid == '':
			break
		# prepare a geo.where object to hold the geographical location
		# of where the video was recorded
		where = gdata.geo.Where()
		where.set_location((37.0,-122.0))
		postid = post.split('/')[6].split('.')[0]
		title=db.getTitleforPost(12345,postid)[0]
		if len(title) > 120:
			title=title[:120]
		# create the gdata.youtube.YouTubeVideoEntry to be uploaded
		
		# prepare a media group object to hold our video's meta-data
		my_media_group = gdata.media.Group(
		  title=gdata.media.Title(text=title),
		  description=gdata.media.Description(description_type='plain',
						      text=postid),
		  keywords=gdata.media.Keywords(text='cgnet,swara,news,community,mobile'),
		  category=[gdata.media.Category(
		      text='News',
		      scheme='http://gdata.youtube.com/schemas/2007/categories.cat',
		      label='news')],
		  player=None
		)
		# prepare a geo.where object to hold the geographical location
		# of where the video was recorded
		where = gdata.geo.Where()
		where.set_location((37.0,-122.0))

		# create the gdata.youtube.YouTubeVideoEntry to be uploaded
		video_entry = gdata.youtube.YouTubeVideoEntry(media=my_media_group,
                                              geo=where)

		# set the path for the video file binary
		video_file_location = post
		new_entry = yt_service.InsertVideoEntry(video_entry, video_file_location)

		youtubeurl = new_entry.media.player.url.split('&')[0]
		print postid + " " + youtubeurl
		youtubetweet(postid,youtubeurl,title)
		os.system("rm -rf %s" %post)
	#print "Final post = " + str(post)
	#os.system("echo %s > lastpushedpost" %(str(post)))
	
	


