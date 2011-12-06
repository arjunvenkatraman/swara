#!/usr/bin/python

import ConfigParser
import tweepy
import oauth2 as oauth
import sys

config=ConfigParser.ConfigParser()
config.read("tweetrcommandline.conf")



OAUTH_TOKEN = config.get("Authentication","oauth_token")
OAUTH_TOKEN_SECRET = config.get("Authentication","oauth_token_secret")
CONSUMER_KEY = config.get("Consumer","consumer_key")
CONSUMER_SECRET = config.get("Consumer","consumer_secret")

if __name__=="__main__":
    auth=tweepy.OAuthHandler(CONSUMER_KEY,CONSUMER_SECRET)
    auth.set_access_token(OAUTH_TOKEN,OAUTH_TOKEN_SECRET)
    api = tweepy.API(auth)
    if len(sys.argv[1]) < 140:
		api.update_status(sys.argv[1])
