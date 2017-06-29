#!/usr/bin/env python
from sys import exit, argv
from googlevoice import Voice
import json, re

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
	phoneNumber = re.sub(r'\D', '', argv[3]).lstrip('1')
except:
	print ('Error! Please provide a phone number')
	voice.logout()
	exit(0)

try:
	text = json.loads(argv[4])
except:
	print ('Error! Please provide a message')
	voice.logout()
	exit(0)

try:
	voice.send_sms(phoneNumber, text)
	print ('Message Sent')
except:
	print ('Error! Message not sent')

voice.logout()
