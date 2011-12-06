import re

def processid(callerid):
    callerid = 912558555856
    cli = str(callerid)
    if (len(cli)>10):
        re.sub('^91', '0', cli)
    print cli
