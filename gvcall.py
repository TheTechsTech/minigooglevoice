#!/usr/bin/env python
from sys import exit, argv
from googlevoice import Voice

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
	outgoingNumber = argv[3]
except:
	print ('Error! Please provide a Number to call ')
	voice.logout()
	exit(0)

try:
	forwardingNumber = argv[4]
except:
	forwardingNumber = None

try:
	numberType = argv[5]
except:
	numberType = None
	
try:
	voice.call(outgoingNumber, forwardingNumber, numberType )
	print ('Calling now...')
except:
	print ('Error! Calling failed')

voice.logout()
