#!/usr/bin/env python
from sys import exit, argv
from googlevoice import Voice
import BeautifulSoup, json

def extractdata(htmldata) :
	"""
    extractdata  --  extract DATA messages from BeautifulSoup tree of Google Voice Output in HTML.

    Output is a list of dictionaries, one per message.
    """
	msgitems = []
	tree = BeautifulSoup.BeautifulSoup(htmldata)
	conversations = tree.findAll("div",attrs={"id" : True}, recursive=False)
	for conversation in conversations :
		rows = conversation.findAll(attrs={"class" : "gc-message-sms-row"})
		for row in rows :
			msgitem = {"id" : conversation["id"]}
			spans = row.findAll("span",attrs={"class" : True}, recursive=False)
			for span in spans :
				cl = span["class"].replace('gc-message-sms-', '')
				msgitem[cl] = (" ".join(span.findAll(text=True))).strip()
			msgitems.append(msgitem)
	return msgitems

voice = Voice()
try:
	email = argv[1]
except:
	print ('Error! Please provide Email Account. ')
	exit(0)
try:
	password = argv[2]
except:
	print ('Error! Please provide Passsword. ')
	exit(0)
try:
	voice.login(email,password)
except:
	print ('Error! Login failed. ')
	exit(0)

try:
	command = argv[3]
except:
	print ('Error! Please provide a FOLDER. ')
	voice.logout()
	exit(0)
	
try:
	ID = argv[4]
except:
	print ('Error! Please provide a Message ID. ')
	voice.logout()
	exit(0)

if command == "Inbox" :
	voice.inbox()
	allmessages = extractdata(voice.inbox.html)
elif command == "Voicemail" :
	voice.voicemail()
	allmessages = extractdata(voice.voicemail.html)
elif command == "Recorded" :
	voice.recorded()
	allmessages = extractdata(voice.recorded.html)
elif command == "Messages" :
	voice.sms()
	allmessages = extractdata(voice.sms.html)
elif command == "All" :
	voice.all()
	allmessages = extractdata(voice.all.html)
elif command == "Spam" :
	voice.spam()
	allmessages = extractdata(voice.spam.html)
elif command == "Trash" :
	voice.trash()
	allmessages = extractdata(voice.trash.html)
elif command == "Placed" :
	voice.placed()
	allmessages = extractdata(voice.placed.html)
elif command == "Received" :
	voice.received()
	allmessages = extractdata(voice.received.html)
elif command == "Missed" :	
	voice.missed()
	allmessages = extractdata(voice.missed.html)

if allmessages != []:
	foundmsg = [msg for msg in allmessages if (msg['id'] == unicode(ID))]

if foundmsg != []:
	foundmsg.sort(key=lambda x:x['time'], reverse=False)
	print json.dumps(foundmsg, indent=4, default=str)
else:
	print ('Nothing found! ')

voice.logout() 
 