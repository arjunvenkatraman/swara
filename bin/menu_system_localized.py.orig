#!/usr/bin/python
# -*- coding: utf-8 -*-
#Menu system
import sys
sys.path.append("/opt/swara/libs")
import smtplib
import stopwatch
import ConfigParser
if len(sys.argv) > 1:
    DEBUG = True
    if sys.argv[1] == 'auto':
        DEBUG_AUTO = True
    elif sys.argv[1] == 'man':
        DEBUG_AUTO = False
    else:
        print 'Usage: ' + sys.argv[0] + ' [auto|man]'
        print ''
        print 'Where no arguments implies the system is running in asterisk,'
        print 'auto runs the automated tests and man runs the keyboard-'
        print 'based manual testbed.'
else:
    DEBUG = False
    DEBUG_AUTO = False
        
import os
import re
import time
import random
import copy
from utilities import *
if DEBUG:
    if DEBUG_AUTO:
        from auto_mockasteriskinterface import *
    else:
        from mockasteriskinterface import *
else:
    from asteriskinterface import *
    from database import *

config=ConfigParser.ConfigParser()
config.read("/etc/swara.conf")
LOGFILE = config.get("System","logfile")
SOUND_DIR = config.get("System","sounddir")
SOUND_DIR = SOUND_DIR + "/"
#PROMPTS_DIR = SOUND_DIR + 'prompts/hindi/'
AST_SOUND_DIR = '/var/lib/asterisk/sounds/'

sys.setrecursionlimit(15000)
##### State functions ######

def login():
    """
    Login: If the user's phone number (var 'user') is unrecognized,
    the system stores it in the "user" table.

    Audio Files:
    """
    global callID
    callID=db.newCall(user)
    debugPrint("Call ID = " + str(callID))
    debugPrint("detected caller id="+user)
    if not db.isUser(user): # If the phone number calling the system is
                            # unrecognized by the database, add the number
                            # as a new user to table 'users'.
        db.addUser(user)
        newUserMessage = str(user) + " ADDED TO DATABASE"
        debugPrint(newUserMessage) # Print message to Asterisk console. NOTE: You must run
                                   # the agi-debug command at console before debug messages
                                   # will appear.
    elif db.isUser(user):
        returningUserMessage = "RETURNING USER " + str(user)
        debugPrint(returningUserMessage)

def mainMenu():
    """
    """
    logger("%s" %language)
    #global language
    callid = db.getID()
    # callid = int(callid) + 1 
    tmm = stopwatch.Timer()
    debugPrint("STARTING MAIN MENU: Region: "+str(region));
    #language = 'hindi'
    #debugPrint("LANGUAGE IS "+language)
    global PROMPTS_DIR
    #Dev's Mod: Makes Hindi the default language
    PROMPTS_DIR = SOUND_DIR + '/prompts/' + (language, 'hindi')[language == None] + '/' 
    #logger("%s" %PROMPTS_DIR)
    keyDict = newKeyDict()
    keyDict['1'] = (addComment,())
    keyDict['2'] = (playBackNews,('skip-post-1',))
    keyDict['3'] = (newsMenu,())
    keyDict['4'] = (invalidDigit,(5, 'Main Menu', tmm,))
    keyDict['5'] = (invalidDigit,(5, 'Main Menu', tmm,))
    keyDict['6'] = (invalidDigit,(6, 'Main Menu', tmm,))
    keyDict['7'] = (invalidDigit,(7, 'Main Menu', tmm,))
    keyDict['8'] = (invalidDigit,(8, 'Main Menu', tmm,))
    keyDict['9'] = (invalidDigit,(9, 'Main Menu', tmm,))
    keyDict['*'] = (playBack,(None, 'playFeatured',))
    try:
        playFile(PROMPTS_DIR+'welcome', keyDict)
        for i in range(1,4):
            playFile(PROMPTS_DIR+'record-1', keyDict)
            playFile(PROMPTS_DIR+'listen-2', keyDict)
            playFile(PROMPTS_DIR+'wait-5-seconds', keyDict) 
        hangup()
    except KeyPressException, e:
        raise

def newsMenu():
    callid = db.getID()
    tmm = stopwatch.Timer()
    debugPrint("---> STARTING NEWS MENU for Region: "+str(region))
    debugPrint(keyPressCLIPrompt("national news", "1"))
    debugPrint(keyPressCLIPrompt("regional news", "2"))
    debugPrint(keyPressCLIPrompt("cultural/entertainment news", "3"))
    global PROMPTS_DIR
    PROMPTS_DIR = SOUND_DIR + '/prompts/' + (language, 'hindi')[language == None] + '/' 
    keyDict = newKeyDict()
    keyDict['1'] = (playBack,(None, 'playNews',))
    keyDict['2'] = (playBack,(None, 'playNews', region,))
    keyDict['3'] = (playBack,(None, 'playEntertainmentNews', region,))
    keyDict['4'] = (invalidDigit,(6, 'Main Menu', tmm,))
    keyDict['5'] = (invalidDigit,(6, 'Main Menu', tmm,))
    keyDict['6'] = (invalidDigit,(6, 'Main Menu', tmm,))
    keyDict['7'] = (invalidDigit,(7, 'Main Menu', tmm,))
    keyDict['8'] = (invalidDigit,(8, 'Main Menu', tmm,))
    keyDict['9'] = (invalidDigit,(9, 'Main Menu', tmm,))
    keyDict['*'] = (playBack,(None, 'playFeatured',))
    playFile(PROMPTS_DIR+'this-cgnet-swara', keyDict)
    for i in range(1,4):
        playFile(PROMPTS_DIR+'record-1', keyDict) # To be replaced by appropriate Audio Files
        playFile(PROMPTS_DIR+'listen-2', keyDict) # To be replaced by appropriate Audio Files
        playFile(PROMPTS_DIR+'wait-5-seconds', keyDict)
    hangup()

def playBackNews( intro=None ):
    callid = db.getID()
    tmm = stopwatch.Timer()
    global PROMPTS_DIR
    PROMPTS_DIR = SOUND_DIR + '/prompts/' + (language, 'hindi')[language == None] + '/' 
    keyDict = newKeyDict()
    keyDict['1'] = (addComment,())
    keyDict['2'] = (playBackNews,('skip-post-1',))
    keyDict['3'] = (newsMenu,())
    keyDict['4'] = (invalidDigit,(5, 'Main Menu', tmm,))
    keyDict['5'] = (invalidDigit,(5, 'Main Menu', tmm,))
    keyDict['6'] = (invalidDigit,(6, 'Main Menu', tmm,))
    keyDict['7'] = (invalidDigit,(7, 'Main Menu', tmm,))
    keyDict['8'] = (invalidDigit,(8, 'Main Menu', tmm,))
    keyDict['9'] = (invalidDigit,(9, 'Main Menu', tmm,))
    keyDict['*'] = (playBack,(None, 'playFeatured',))
    
    debugPrint('Playing national news.')
    playBack(intro, 'playNews', 'national')
    playFile(PROMPTS_DIR+'wait-5-seconds', keyDict) # Pause after national news. Can be replaced by appropriate audio prompt.
    
    debugPrint('Playing news from region ' + region)
    playBack(intro, 'playNews', region)
    playFile(PROMPTS_DIR+'wait-5-seconds', keyDict) # Pause after regional news. Can be replaced by appropriate audio prompt.
    
    debugPrint('Playing entertainment news.')
    playBack(intro, 'playEntertainmentNews', region)
    

def playBack(intro=None, mode='playBack', playback_region='national'):
    """
    SINGLE PLAYBACK ROUTINE FOR 'playBack', 'playNews', 'playFeatured', 'playEntertainmentNews'
    """
    keyDict = newKeyDict()
    if mode == 'playBack':
        posts = db.getPostsInChannel('12345')
        debugPrint( "Playing Messages!" );
    elif mode == 'playNews':
        debugPrint("Getting posts for region %s" %playback_region)
        posts = db.getRegionalPosts(playback_region)
        debugPrint( "Playing News from '"+playback_region+"' region!" );
    elif mode == 'playFeatured':
        posts = db.getFeaturedPosts()
        debugPrint( "Playing Featured Messages!" );
    elif mode == 'playEntertainmentNews':
        posts = db.getEntertainmentPosts()
        debugPrint( "Playing Cultural/Entertainment Messages!" );

    if len(posts) == 0:
        return playFile(PROMPTS_DIR+'/no-comments', keyDict)
    playFile(PROMPTS_DIR+'mistake-0', keyDict)
    playFile(PROMPTS_DIR+intro, keyDict)
    count = 0
    for postID in posts:
        tpb = stopwatch.Timer()
        count = count + 1
        if (count==5):
            break
        keyDict['1'] = (skipComment,(postID, tpb))
        keyDict['3'] = (invalidDigit,(3, 'Playback', tpb,))
        keyDict['4'] = (invalidDigit,(4, 'Playback', tpb,))
        keyDict['5'] = (invalidDigit,(5, 'Playback', tpb,))
        keyDict['6'] = (invalidDigit,(6, 'Playback', tpb,))
        keyDict['7'] = (invalidDigit,(7, 'Playback', tpb,))
        keyDict['8'] = (invalidDigit,(8, 'Playback', tpb,))
        keyDict['9'] = (invalidDigit,(9, 'Playback', tpb,))
        keyDict['*'] = (playFeatured,())
        commentFile = SOUND_DIR+str(postID)
        keyPress = playFile(commentFile, keyDict)
        if keyPress == '1': # If user presses 1, skip to next comment.
            pass
        db.addPlaybackEvent(postID, tpb)
    tpbm = stopwatch.Timer()
    playFile(PROMPTS_DIR+'for-older-posts')
    keyDict2 = newKeyDict()
    keyDict2['1'] = (addComment,())
    keyDict2['2'] = (playBack,('skip-post-1',))
    keyDict2['3'] = (invalidDigit,(3, 'Main Menu after Playback', tpbm,))
    keyDict2['4'] = (invalidDigit,(4, 'Main Menu after Playback', tpbm,))
    keyDict2['5'] = (invalidDigit,(5, 'Main Menu after Playback', tpbm,))
    keyDict2['6'] = (invalidDigit,(6, 'Main Menu after Playback', tpbm,))
    keyDict2['7'] = (invalidDigit,(7, 'Main Menu after Playback', tpbm,))
    keyDict2['8'] = (invalidDigit,(8, 'Main Menu after Playback', tpbm,))
    keyDict2['9'] = (invalidDigit,(9, 'Main Menu after Playback', tpbm,))
    keyDict2['*'] = (playFeatured,())
    playFile(PROMPTS_DIR+'this-cgnet-swara', keyDict2)
    for i in range(1,4):
        playFile(PROMPTS_DIR+'record-1', keyDict2)
        playFile(PROMPTS_DIR+'listen-2', keyDict2)
        playFile(PROMPTS_DIR+'wait-5-seconds', keyDict2)
    hangup()

def invalidDigit(key, context, time):
    # keyDict3 = newKeyDict()
    # playFile(PROMPTS_DIR+'this-cgnet-swara', keyDict3)
    db.addInvalidkeyEvent(key, context, time, callID)
    #db.addInvalidkeyEvent(key, context, time) ARJUN PATCHED
    # if (str(context)=='mainMenu'):
        # try:
            # playFile(PROMPTS_DIR+'welcome',keyDict)
            # for i in range(1,4):
                # playFile(PROMPTS_DIR+'record-1', keyDict)
            # hangup()
    # elif (str(context)=='playBack'):
        # playBack()
    # elif (str(context)=='addComment'):
        # addComment()
    # else:
        # login()

def skipComment(commentID, time):
    debugPrint("SKIPPING COMMENT "+str(commentID))
    db.skipComment(int(commentID))
    #db.addSkipEvent(commentID, time) ARJUN PATCHED
    db.addSkipEvent(commentID, time, callID)

def addComment():
    playFile(PROMPTS_DIR+'mistake-0')
    while True:
        commentTempFileName = recordFileNoPlayback(PROMPTS_DIR+'record-message-beep',300)
        if commentTempFileName:
            break
    logger(commentTempFileName)
    newCommentID = db.addCommentToChannel(user, '12345')
    os.rename(SOUND_DIR+"/"+commentTempFileName+".wav", SOUND_DIR+str(newCommentID)+".wav")
    os.system("lame -h --abr 200 "+SOUND_DIR+str(newCommentID)+".wav "+SOUND_DIR+"/web/" \
                                                            + str(newCommentID)+".mp3")
    #db.addMessageRecordEvent(newCommentID) ARJUN PATCHED 
    logger(newCommentID)
    logger(callID)
    db.addMessageRecordEvent(newCommentID,callID) 
    # server = smtplib.SMTP('smtp.gmail.com:587')
    # server.ehlo()
    # server.starttls()
    # server.ehlo()
    # server.login(username,password)
    # server.sendmail(fromaddr, toaddrs, msg)
    # server.quit()
    logger("Added message record event")
    trm = stopwatch.Timer()
    playFile(PROMPTS_DIR+'thank-you-submitted')
    keyDict2 = newKeyDict()
    keyDict2['1'] = (addComment,())
    keyDict2['2'] = (playBack,('skip-post-1',))
    keyDict2['3'] = (invalidDigit,(3, 'Main Menu after Recording', trm,))
    keyDict2['4'] = (invalidDigit,(4, 'Main Menu after Recording', trm,))
    keyDict2['5'] = (invalidDigit,(5, 'Main Menu after Recording', trm,))
    keyDict2['6'] = (invalidDigit,(6, 'Main Menu after Recording', trm,))
    keyDict2['7'] = (invalidDigit,(7, 'Main Menu after Recording', trm,))
    keyDict2['8'] = (invalidDigit,(8, 'Main Menu after Recording', trm,))
    keyDict2['9'] = (invalidDigit,(9, 'Main Menu after Recording', trm,))
    keyDict2['*'] = (playBack,(None, 'playFeatured',))
    for i in range(1,4):
        playFile(PROMPTS_DIR+'this-cgnet-swara', keyDict2)
        playFile(PROMPTS_DIR+'record-1', keyDict2)
        playFile(PROMPTS_DIR+'listen-2', keyDict2)
        playFile(PROMPTS_DIR+'wait-5-seconds', keyDict2)
    hangup()

def playFeatured():
    keyDict = newKeyDict()
    posts = db.getFeaturedPosts()
    debugPrint( "Playing Featured Messages!" );
    if len(posts) == 0:
        return playFile(PROMPTS_DIR+'/no-comments', keyDict)
    playFile(PROMPTS_DIR+'mistake-0', keyDict)
    count = 0
    for postID in posts:
        tpb = stopwatch.Timer()
        count = count + 1
        if (count==5):
            break
        keyDict['1'] = (skipComment,(postID, tpb))
        keyDict['3'] = (invalidDigit,(3, 'Playback', tpb,))
        keyDict['4'] = (invalidDigit,(4, 'Playback', tpb,))
        keyDict['5'] = (invalidDigit,(5, 'Playback', tpb,))
        keyDict['6'] = (invalidDigit,(6, 'Playback', tpb,))
        keyDict['7'] = (invalidDigit,(7, 'Playback', tpb,))
        keyDict['8'] = (invalidDigit,(8, 'Playback', tpb,))
        keyDict['9'] = (invalidDigit,(9, 'Playback', tpb,))
        keyDict['*'] = (playFeatured,())
        commentFile = SOUND_DIR+str(postID)
        keyPress = playFile(commentFile, keyDict)
        if keyPress == '1': # If user presses 1, skip to next comment.
            pass
        db.addPlaybackEvent(postID, tpb)
    tpbm = stopwatch.Timer()
    playFile(PROMPTS_DIR+'for-older-posts')
    keyDict2 = newKeyDict()
    keyDict2['1'] = (addComment,())
    keyDict2['2'] = (playBack,('skip-post-1',))
    keyDict2['3'] = (invalidDigit,(3, 'Main Menu after Playback', tpbm,))
    keyDict2['4'] = (invalidDigit,(4, 'Main Menu after Playback', tpbm,))
    keyDict2['5'] = (invalidDigit,(5, 'Main Menu after Playback', tpbm,))
    keyDict2['6'] = (invalidDigit,(6, 'Main Menu after Playback', tpbm,))
    keyDict2['7'] = (invalidDigit,(7, 'Main Menu after Playback', tpbm,))
    keyDict2['8'] = (invalidDigit,(8, 'Main Menu after Playback', tpbm,))
    keyDict2['9'] = (invalidDigit,(9, 'Main Menu after Playback', tpbm,))
    keyDict2['*'] = (playFeatured,())
    playFile(PROMPTS_DIR+'this-cgnet-swara', keyDict2)
    for i in range(1,4):
        playFile(PROMPTS_DIR+'record-1', keyDict2)
        playFile(PROMPTS_DIR+'listen-2', keyDict2)
        playFile(PROMPTS_DIR+'wait-5-seconds', keyDict2)
    hangup()


def recordFileNoPlayback(introFilename, recordLen=30000):
    keyDict = newKeyDict()
    name = os.tmpnam()
    name = name[len('/tmp/'):]
    try:
        playFile(introFilename,keyDict)
        recordFile(name, '#1', recordLen, 5)
        # The following code makes it possible for the user to confirm his submission.
        # playFile(name, keyDict)
        #while True:
        #    keyDict['1']= Nop
        #    keyDict['2']= (removeTempFile,(AST_SOUND_DIR+"/"+name+'.wav',))
        #    key = playFileGetKey(PROMPTS_DIR+'submit-or-rerecord', 5000, 1, keyDict)
        #    if key == '1':
        #        return name
        #    elif key == '2':
        #        return None
        #    else:
        #        playFile(PROMPTS_DIR+'not-understood')
        return name
    except KeyPressException, e:
        if name and os.path.exists(AST_SOUND_DIR+name+'.wav'):
            os.remove(AST_SOUND_DIR+name+'.wav')
        raise
##Logger function - Arjun##
def logger(message):
	timestamp=os.popen("date +%d-%m-%Y-%H:%M:%S").read().strip()
	os.system("echo %s %s >> %s" %(timestamp,message,LOGFILE))


### Procedural code starts here ###

if __name__=='__main__':
    logger("Beginning execution")
    if DEBUG:
        db = Database(db_name='test',db_port=3306)
        user = '123456'
    else:
            # Read and ignore AGI environment (read until blank line)
        env = {}
        tests = 0;
        while True:
            line = sys.stdin.readline().strip()
            if line == '':
                break
            key,data = line.split(':')
            if key[:4] != 'agi_':
                #Arjun Debug
		#skip input that doesn't begin with agi_
                sys.stderr.write("Did not work!\n")
                logger("Did not work!\n")
                sys.stderr.flush()
                continue
            key = key.strip()
            data = data.strip()
            #Arjun Debug
            #logger("%s:%s" %(key,data))
            if key != '':
                env[key] = data
        #sys.stderr.write("AGI Environment Dump:\n")
        #Arjun Debug
        #sys.stderr.flush()
        for key in env.keys():
            #sys.stderr.write(" -- %s = %s\n" % (key, env[key]))
            #Arjun Debug
            logger(" -- %s = %s\n" % (key, env[key]))
            #sys.stderr.flush()
        db = Database()
        user = env['agi_callerid']
        logger("User: %s" %user)
    login()
    #logger("%s logged in" %user)
    #circle=db.getCircleForSeries(user[:4])
    #logger("User %s" %user[:4])
    #logger("%s" %circle)
    #language=db.getLanguageForCircle(circle)
    language=db.getLanguageForSeries(user[:4])
    region=db.getRegionForSeries(user[:4])
    #if region != "":
	#debugPrint("REGION::::"+region)
    #logger("%s" %language)
    while True:
        try:
            mainMenu()
        except KeyPressException, e:
            if e.key != '0':
                #db.addInvalidkeyEvent(e.key, 'mm', 5) ARJUN PATCHED
                db.addInvalidkeyEvent(e.key, 'mm', 5,callID)
            else:
                continue
