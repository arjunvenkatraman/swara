#DB Functions

#import * safe
import MySQLdb
import re
from utilities import *
import ConfigParser

config=ConfigParser.ConfigParser()
config.read("/etc/swara.conf")
DB_USER = config.get("Database","username")
DB_PASSWD = config.get("Database","password")
DB_HOST = config.get("Database","host")
DB_PORT = int(config.get("Database","port"))
DB_NAME = config.get("Database","dbname")

class Database:
    def __init__(self,db_port=DB_PORT,db_host=DB_HOST,
                db_user=DB_USER,db_passwd=DB_PASSWD,db_name=DB_NAME):
        self.db = MySQLdb.connect(port=db_port,host=db_host,
                                user=db_user,passwd=db_passwd)
        self.c = self.db.cursor()
        self.c.execute('USE '+db_name+';')
        
    def channelExists(self, channelNum):
        count = self.c.execute("SELECT * FROM stations WHERE number = %s",
                                (str(channelNum),))
        return count>0
        
    def getPostsInChannel(self, channelNum):
        self.c.execute("SELECT * FROM lb_postings WHERE station = %s and status = 3 ORDER BY posted DESC;",
						(str(channelNum),))
        posts = self.c.fetchall()
        posts = [i[0] for i in posts]
        return posts
    def getPostDetails(self, channelNum,post):
        self.c.execute("SELECT * FROM lb_postings WHERE station = %s and id=%s;",(str(channelNum),str(post),))
        posts = self.c.fetchall()
        #posts = [i[0] for i in posts]
        return posts
    def getAllPostsInChannel(self, channelNum):
        self.c.execute("SELECT * FROM lb_postings WHERE station = %s ORDER BY posted DESC;",
						(str(channelNum),))
        posts = self.c.fetchall()
        posts = [i[0] for i in posts]
        return posts
    
    def getTagsforPost(self, channelNum, post):
        self.c.execute("SELECT tags  FROM lb_postings WHERE station = %s and id=%s;",(str(channelNum),str(post),))
        tags = self.c.fetchall()
        tags = [i[0] for i in tags]
        return tags

    def getTitleforPost(self, channelNum, post):
        self.c.execute("SELECT title  FROM lb_postings WHERE station = %s and id=%s;",(str(channelNum),str(post),))
        title = self.c.fetchall()
        title = [i[0] for i in title]
        return title
    
    def getMessageforPost(self, channelNum, post):
        self.c.execute("SELECT message_input  FROM lb_postings WHERE station = %s and id=%s;",(str(channelNum),str(post),))
        message = self.c.fetchall()
        message = [i[0] for i in message]
        return message
    def getLengthforPost(self, channelNum, post):
        self.c.execute("SELECT audio_length  FROM lb_postings WHERE station = %s and id=%s;",(str(channelNum),str(post),))
        length = self.c.fetchall()
        length = [i[0] for i in length]
        return length
    
    def deletePost(self, postID):
        self.c.execute("DELETE FROM lb_postings WHERE id = %s;",
                        (str(postID),))
        self.db.commit()


    def publishPost(self, postID):
        self.c.execute("UPDATE lb_postings SET status = 3 WHERE id = %s;",
						(str(postID),))
        self.db.commit()

    def updatePostLength(self, length,postID):
        self.c.execute("UPDATE lb_postings SET audio_length = %s WHERE id = %s;",(str(length),str(postID),))
        self.db.commit()
    
    def archivePost(self, postID):
        self.c.execute("UPDATE lb_postings SET status = 2 WHERE id = %s;",
						(str(postID),))
	self.db.commit()

    def newCall(self, user):
        self.c.execute("INSERT INTO callLog (user) values (%s);",(str(user),))
        self.db.commit()
        #Arjun patched for analytics
        self.c.execute("SELECT LAST_INSERT_ID() FROM callLog;")
        callID=self.c.fetchall()
        callID=[i[0] for i in callID]
        return callID[0]


    def addUser(self, phoneNumberString):
        self.c.execute("INSERT INTO users (phone_number) " + \
                       "VALUES (%s);",(str(phoneNumberString),))
        self.db.commit()

    def isUser(self, phoneNumberString):
        count = self.c.execute(
            "SELECT phone_number FROM users WHERE phone_number = %s;"
                                            ,(str(phoneNumberString),))
        return count>0

    def getCommentIDs(self):
        self.c.execute("""SELECT id from lb_postings WHERE status = 3 \
                        ORDER BY posted DESC;""")
        # Select the comments that haven't been archived.
        comments = self.c.fetchall()
        comments = [i[0] for i in comments]
        return comments

    def getAllCommentIDs(self):
        self.c.execute("""SELECT id from lb_postings ORDER BY posted DESC;""")
        # Select the comments that haven't been archived.
        comments = self.c.fetchall()
        comments = [i[0] for i in comments]
        return comments

   
    def addCommentToChannel(self, phoneNum, channel):
		    #self.c.execute("INSERT INTO lb_postings (user, station) VALUES (%s, %s);",(phoneNum, str(channel),))
		    #Arjun changed to autopublish
                    self.c.execute("INSERT INTO lb_postings (user, station, status,author_id,message_input,edited,audio_size,message_html) VALUES (%s, %s, 3, 0, Empty, 0, 0,Empty);",(phoneNum, str(channel),))
		    self.db.commit()
		    ids = str(self.c.lastrowid)
		    extension = '.mp3'	
		    filename = ids + extension
		    print filename
		    self.c.execute("UPDATE lb_postings SET audio_file = %s WHERE id = %s;",(filename, ids)) 
		    self.db.commit()
		    return ids
		
    def addComment(self, phoneNum):
        self.c.execute("INSERT INTO lb_postings (user) VALUES (%s);", \
                       (phoneNum))
        self.db.commit()
        return self.c.lastrowid

    def skipComment(self, commentID):
        self.c.execute(
            "UPDATE lb_postings SET skip_count = skip_count + 1 WHERE id = %s;",
            (commentID))
        self.db.commit()
        debugPrint("SKIPPED "+str(commentID))
		
    def addPlaybackEvent(self, postID, duration, callid):
        self.c.execute("INSERT INTO analytics (eventype, msglstnd, durlistndto, callid) VALUES (%s, %s, %s, %s);",('Listened', str(postID), str(duration),str(callid),))
        self.db.commit()
        return self.c.lastrowid

    def addSkipEvent(self, postID, duration,callid):
        self.c.execute("INSERT INTO analytics (eventype, msglstnd, durlistndto, callid) VALUES (%s, %s, %s, %s);",('Skipped', str(postID), str(duration), str(callid),))
        self.db.commit()

    def addInvalidkeyEvent(self, key, when, duration, callid):        
        self.c.execute("INSERT INTO analytics (eventype, invdgtpsd, context, whenpressed,callid) VALUES (%s, %s, %s, %s);" ,('Invalid Keypress', str(key), str(when), str(duration),str(callid),))
        self.db.commit()

    def addMessageRecordEvent(self, postID,callid):
        self.c.execute("INSERT INTO analytics (eventype, msgrcd, callid) VALUES (%s, %s,%s);",('Recorded', str(postID),str(callid),))
        self.db.commit()

    def getID(self):
        self.c.execute("""SELECT id FROM cdr ORDER BY calldate DESC LIMIT 1;""")
        callidno = self.c.fetchall()

    def getSMSSubscribers(self):
				self.c.execute("""SELECT phone_number FROM users WHERE DATE_SUB(CURDATE(),INTERVAL 2 week) <= lastloggedin;""")
				numberlist = self.c.fetchall()
				numberlist = [i[0] for i in numberlist]
				return numberlist

    def logUserTime(self,user):
        self.c.execute("UPDATE users SET lastloggedin=NOW() WHERE phone_number=%s;",(user,))
        self.db.commit()
    
    def updateLastSMS(self,user):
        self.c.execute("UPDATE users SET lastsmsed=NOW() WHERE phone_number=%s;",(user,))
        self.db.commit()

    def getSummaryforPost(self, channelNum, post):
        self.c.execute("SELECT sms_summary  FROM lb_postings WHERE station = %s and id=%s;",(str(channelNum),str(post),))
        title = self.c.fetchall()
        title = [i[0] for i in title]
        if title[0]==None:
            title="NULL"
        return title
			
    def getUnSMSedPostsInChannel(self, channelNum, lastid):
        self.c.execute("SELECT * FROM lb_postings WHERE station = %s and id > %s and status = 3 ORDER BY posted;",(str(channelNum),str(lastid),))
        posts = self.c.fetchall()
        posts = [i[0] for i in posts]
        return posts
    
    def getPostedTime(self, channelNum, postid):
        self.c.execute("SELECT posted FROM lb_postings WHERE station = %s and id = %s;",(str(channelNum),str(postid),))
        posttime = self.c.fetchall()
        posttime = [i[0] for i in posttime]
        return posttime[0]
    
    def getUnpushedPostsInChannel(self, channelNum, lastid):
        time=self.getPostedTime(channelNum,lastid)
        self.c.execute("SELECT id FROM lb_postings WHERE station = %s and posted > TIMESTAMP(%s) AND status = 3 ORDER BY posted;",(str(channelNum),str(time),))
        posts = self.c.fetchall()
        posts = [i[0] for i in posts]
        return posts

