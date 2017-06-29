#!/usr/bin/env python
from sys import exit, argv
from googlevoice import Voice
from os import path, makedirs
import json

voice = Voice()
try:
	email = argv[1]
except:
	print ('Error! Please provide Email Account.')
	exit(0)

try:
	password = argv[2]
except:
	print ('Error! Please provide Passsword.')
	exit(0)

try:
	voice.login(email,password)
except:
	print ('Error! Login failed.')
	exit(0)
	
try:
	for message in voice.recorded().messages:
		directory = "googlerecorded/" + message.id
		if not path.isdir(directory):
			makedirs(directory)
			message.download(directory)
	folder = voice.recorded().messages
	foundmsg = [ msg for msg in folder]
	if foundmsg != []:
		foundmsg.sort(key=lambda x:x['displayStartDateTime'], reverse=True)
		print json.dumps(foundmsg, indent=4, default=str)
except:
	print ("Error! Recordings not downloaded.")

voice.logout
