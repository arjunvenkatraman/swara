#!/usr/bin/python
import os,sys,time,datetime
sys.path.append("/opt/swara/libs")
from database import *
config=ConfigParser.ConfigParser()
config.read("/etc/swara.conf")
STAT_DIR = config.get("System","statdir")
STAT_DIR=STAT_DIR+"/"

def genCSVCallsByDate(ts):
    ofile="%sCallsByDate-%s.csv" %(STAT_DIR,ts)
    file=open(ofile,"w")
    file.write("Date,Number of Calls\n")
    calls=db.getNumCallsByDate()
    for call in calls:
        file.write( "%s,%s\n" %(call[0],call[1]))
    file.close()
    os.system("rm %sCallsByDate.csv;ln -s %s %sCallsByDate.csv" %(STAT_DIR,ofile,STAT_DIR))
def genCSVCallsByCircle(ts):
    ofile="%sCallsByCircle-%s.csv" %(STAT_DIR,ts)
    file=open(ofile,"w")
    file.write("Circle,Number of Calls\n")
    calls=db.getNumCallsByCircle()
    for call in calls:
        file.write( "%s,%s\n" %(call[0],call[1]))
    file.close()
def genCSVCallsByProvider(ts):
    ofile="%sCallsByProvider-%s.csv" %(STAT_DIR,ts)
    file=open(ofile,"w")
    file.write("Provider,Number of Calls\n")
    calls=db.getNumCallsByProvider()
    for call in calls:
        file.write( "%s,%s\n" %(call[0],call[1]))
    file.close()
    os.system("rm %sCallsByProvider.csv;ln -s %s %sCallsByProvider.csv" %(STAT_DIR,ofile,STAT_DIR))
if __name__=="__main__":
    db = Database()
    #Generate CSV of calls by date
    ts=time.strftime("%Y%m%d-%H%M")
    genCSVCallsByDate(ts)
    genCSVCallsByCircle(ts)
    genCSVCallsByProvider(ts)
    #Generate 
    #nums=db.getChannelNums()
    #print nums
    #for i in nums:
        #print str(i)
        #print "**********************"
        #posts=db.getPostsInChannel(i)
        #print posts
    #posts=db.getPostsInChannel(12345)
    #print posts
    #numbers=db.getUsers()
    #for number in numbers:   
        #print number[:4]
    #    circle=db.getCircleForSeries(number[:4])
        #print number,circle
    #    if circle!=None:
    #        print "%s,%s" %(number,circle[0].strip('\n').strip('\r'))
    #   else:
    #       print number
    #file = open(sys.argv[1],'r')
    #serieslist=file.readlines()
    #for item in serieslist:
    #    tuple=item.split(',')
    #    print "%s|%s|%s" %(tuple[0], tuple[1], tuple[2])
    #    db.addCircleData(tuple[0],tuple[1],tuple[2])
    #print serieslist
    #print db.getLanguageForCircle('KA')
    
