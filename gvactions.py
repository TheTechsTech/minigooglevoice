#!/usr/bin/env python
from sys import exit, argv
from googlevoice import Voice
import json

def doactions(count, gvaction, msgID, getID) :
	if ((gvaction == "Deleted") and (msgID in getID)) :
		message.delete()
		count = count + 1
	elif ((gvaction == "unDeleted") and (msgID in getID)) :
		message.delete(0)
		count = count + 1
	elif ((gvaction == "unArchived") and (msgID in getID)) :
		message.archive(0)
		count = count + 1
	elif ((gvaction == "Archived") and (msgID in getID)) :
		message.archive()
		count = count + 1
	elif ((gvaction == "markUnread") and (msgID in getID)) :
		message.mark(0)
		count = count + 1
	elif ((gvaction == "markRead") and (msgID in getID)) :
		message.mark()
		count = count + 1
	elif ((gvaction == "unStarred") and (msgID in getID)) :
		message.star(0)
		count = count + 1
	elif ((gvaction == "Starred") and (msgID in getID)) :
		message.star()
		count = count + 1
	elif ((gvaction == "unBlocked") and (msgID in getID)) :
		message.block(0)
		count = count + 1
	elif ((gvaction == "Blocked") and (msgID in getID)) :
		message.block()
		count = count + 1
	elif ((gvaction == "unNoted") and (msgID in getID)) :
		message.deleteNote()
		count = count + 1
	return count
	
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
	print ('Error! Please provide a Message Folder an ACTION and Message ID. ')
	voice.logout()
	exit(0)

try:
	ACTION = argv[4]
except:
	print ('Error! Please provide a ACTION and Message ID. ')
	voice.logout()
	exit(0)
try:
	ID = json.loads(argv[5])
except:
	print ('Error! Please provide a Message ID. ')
	voice.logout()
	exit(0)

counter = 0
try:
	if msgfolder == "Inbox" :
		msglen = len(voice.inbox().messages)
		for message in voice.inbox().messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "Voicemail" :
		msglen = len(voice.voicemail().messages)
		for message in voice.voicemail().messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "Recorded" :
		msglen = len(voice.recorded().messages)
		for message in voice.recorded().messages: 
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "Messages" :
		msglen = len(voice.sms().messages)
		for message in voice.sms().messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "All" :
		voice.all()
		folder = voice.all.folder
		msglen = len(folder.messages)
		for message in folder.messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "Placed" :
		msglen = len(voice.placed().messages)
		for message in voice.placed().messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "Recieved" :
		msglen = len(voice.recieved().messages)
		for message in voice.recieved().messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "Spam" :
		voice.spam()
		folder = voice.spam.folder
		msglen = len(folder.messages)
		for message in folder.messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "Trash" :
		voice.trash()
		folder = voice.trash.folder
		msglen = len(folder.messages)
		for message in folder.messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
	elif msgfolder == "Missed" :
		msglen = len(voice.missed().messages)
		for message in voice.missed().messages:
			counter = doactions(counter, ACTION, message.id , ID)
			if (msglen == counter) :
				break
except: 
	print ("Error! With" + msgfolder +", " + ACTION + " and " + ID + "Message ID's.")
 
print ACTION + " " + str(counter) + " Messages."
voice.logout()
