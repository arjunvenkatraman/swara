#!/usr/bin/python

# Requires tweepy,simplejson and oauth0
# further requires a config file (tweetrswara.conf) with the access token for the swara user

import os,sys,time
from database import *
from utilities import *
import ConfigParser
import tweepy
import oauth2 as oauth
import os,sys
import smtplib
from email.MIMEMultipart import MIMEMultipart
from email.MIMEBase import MIMEBase
from email.MIMEText import MIMEText
from email import Encoders

config=ConfigParser.ConfigParser()
config.read("/home/swara/audiowiki/twittercredsswara.conf")
gmail_user = "<myuser@gmail.com>"
gmail_pwd = "<mypassword>"

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

def tweet(post):
	title=db.getTitleforPost(12345,post)[0][:120]
	title=title.replace("&#039;","'")
	url="http://cgnetswara.org/index.php?id="+str(post)
	print title + " " + url
	status=title +" "+ url
	try:
		api.update_status(status)
	except:
		print "Failed to tweet %s" %status
	#debugPrint("Tweeted post %s" %post)

def mail(post,to):
	title=db.getTitleforPost(12345,post)[0][:120]
	title=title.replace("&#039;","'")
	subject="[CGNet Swara]" + title 
	content=db.getMessageforPost(12345,post)[0]
	content=content.replace("&#039;","'")
	authorname=db.getMessageforPost(12345,post)[0]
	if '_' in authorname:
		authorname=authorname.split('_')[0]
	text="Dear friends,\n \n"+content+"\n \nhttp://www.cgnetswara.org/index.php?id="+str(post)+"\n\nYou can also listen to this post after leaving a missed call on 080 4113 7280.\nYou can also record your own messages/songs the same way using your phone as this user has done\nRegards\nCGnet Swara moderators team" 	
	attach=''
	msg = MIMEMultipart()
	msg['From'] = "CGnet Swara"
	msg['To'] = to
	msg['Subject'] = subject
	msg.attach(MIMEText(text))
	if attach != "":
		part = MIMEBase('application', 'octet-stream')
		part.set_payload(open(attach, 'rb').read())
		Encoders.encode_base64(part)
		part.add_header('Content-Disposition',
			  'attachment; filename="%s"' % os.path.basename(attach))
		msg.attach(part)
	mailServer = smtplib.SMTP("smtp.gmail.com", 587)
	mailServer.ehlo()
	mailServer.starttls()
	mailServer.ehlo()
	mailServer.login(gmail_user, gmail_pwd)
	mailServer.sendmail(gmail_user, to, msg.as_string())
	# Should be mailServer.quit(), but that crashes...
	mailServer.close()

if __name__=="__main__":
	#Create Database object
	postid=getLastPushedPostID() 
	print postid
	posts=db.getUnpushedPostsInChannel(12345,postid)
	if len(posts) == 0:
		print "No unpushed posts"
		exit()
	for post in posts:
		try:
			tweet(post)
		except:
			print "Unicode Problem with post %s" %post
			continue
		mail(post,"arjun@cgnet.in")
		mail(post,"chhattisgarh-net@yahoogroups.com")
		
	for post in posts:	
		os.system("/home/swara/audiowiki/mp32avi.sh /home/swara/audiowiki/SwaraCoverLarge.jpg /home/swara/audiowiki/web/sounds/web/%s.mp3 /home/swara/audiowiki/web/videos/%s.avi" %(str(post),str(post)))
	print "Final post = " + str(post)
	os.system("echo %s > lastpushedpost" %(str(post)))
