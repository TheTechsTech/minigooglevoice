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
	msgfolder = argv[3]
except:
	print ('Error! Please provide a Message Folder an  Message ID and NOTES. ')
	voice.logout()
	exit(0)

try:
	ID = argv[4]
except:
	print ('Error! Please provide a Message ID and NOTES. ')
	voice.logout()
	exit(0)
try:
	NOTES = json.loads(argv[5])
except:
	print ('Error! Please provide NOTES. ')
	voice.logout()
	exit(0)

try:
	if msgfolder == "Voicemail" :
		for message in voice.voicemail().messages:
			if (message.id == ID) :
				message.addNote(NOTES)
				break
	elif msgfolder == "Recorded" :
		for message in voice.recorded().messages:
			if (message.id == ID) :
				message.addNote(NOTES)
				break
except: 
	print ("Error! With" + msgfolder + ", Message ID:" + ID + " and Notes:" + NOTES )
 
print "Notes Added!"
voice.logout()
