#!/usr/bin/env python
from sys import exit, argv
from googlevoice import Voice, settings
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
	print ('Error! Please provide COMMAND. ')
	voice.logout()
	exit(0)

data = voice.all()	
if command == "SETTING":
	print json.dumps(voice.settings, indent=4, default=str)
if command == "PHONES":
	print json.dumps(voice.phones, indent=4, default=str)
if command == "UNREAD":
	print json.dumps(data.unreadCounts, indent=4, default=str)

voice.logout() 
 