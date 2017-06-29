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
	print ('Error! Please provide a phone number or FOLDER. ')
	voice.logout()
	exit(0)

if command == "Inbox" :
	folder = voice.inbox().messages
	foundmsg = [ msg for msg in folder]
elif command == "Messages" :
	folder = voice.sms().messages
	foundmsg = [ msg for msg in folder]
elif command == "All" :
	voice.all()
	folder = voice.all.folder
	foundmsg = [ msg for msg in folder.messages]
elif command == "Spam" :	
	voice.spam()
	folder = voice.spam.folder
	foundmsg = [ msg for msg in folder.messages]
elif command == "Trash" :
	voice.trash()
	folder = voice.trash.folder
	foundmsg = [ msg for msg in folder.messages]
elif command == "Placed" :
	folder = voice.placed().messages
	foundmsg = [ msg for msg in folder]
elif command == "Recieved" :
	voice.recieved()
	folder = voice.recieved.messages
	foundmsg = [ msg for msg in folder]
elif command == "Missed" :	
	folder = voice.missed().messages
	foundmsg = [ msg for msg in folder]
#elif command == "History" :
	#try:
		#phone = argv[4]
		#folder = voice.search(phone)
		#foundmsg = [ msg for msg in folder.messages if (msg.messageText != "") ]
	#except:
		#print ('Error! Please provide a phone number. ')
		#voice.logout()
		#exit(0)
else:
	folder = voice.search(command)
	foundmsg = [ msg for msg in folder.messages]

if foundmsg != []:
	foundmsg.sort(key=lambda x:x['displayStartDateTime'], reverse=True)
	print json.dumps(foundmsg, indent=4, default=str)
else:
	print('Nothing found! ')
 
voice.logout()
