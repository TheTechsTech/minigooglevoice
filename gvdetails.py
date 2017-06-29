#!/usr/bin/env python
from sys import exit, argv
from googlevoice import Voice
import json

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
	print ('Error! Please provide a COMMAND. ')
	voice.logout()
	exit(0)
	
try:
	ID = argv[4]
except:
	print ('Error! Please provide a Message ID or Phone Number. ')
	voice.logout()
	exit(0)
		
if command == "Inbox" :
	folder = voice.inbox().messages
	foundmsg = [ msg for msg in folder]
elif command == "Voicemail" :
	folder = voice.voicemail().messages
	foundmsg = [ msg for msg in folder]
elif command == "Recorded" :
	folder = voice.recorded().messages
	foundmsg = [ msg for msg in folder]
elif command == "Messages" :
	folder = voice.sms().messages
	foundmsg = [ msg for msg in folder]
elif command == "All" :
	folder = voice.all().messages
	foundmsg = [ msg for msg in folder]
elif command == "Spam" :	
	folder = voice.spam().folder
	foundmsg = [ msg for msg in folder.messages]
elif command == "Trash" :
	folder = voice.trash().folder
	foundmsg = [ msg for msg in folder.messages]
elif command == "Placed" :
	folder = voice.placed().messages
	foundmsg = [ msg for msg in folder]
elif command == "Recieved" :
	folder = voice.recieved().messages
	foundmsg = [ msg for msg in folder]
elif command == "Missed" :	
	folder = voice.missed().messages
	foundmsg = [ msg for msg in folder]
elif command == "Phone" :
	folder = voice.search(ID).messages
	foundmsg = [dict(msg) for msg in folder if (msg.messageText != "")]
	getID = [ msg.id for msg in folder if (msg.messageText != "")]
	ID = ''.join(getID)
	foundmsg = folder

try:
	for message in foundmsg:
		if message.id == ID :
			message.mark()
			print json.dumps(message, indent=4, default=str)
			break
except:
	print ('Error! With Message ID. ')

voice.logout()
