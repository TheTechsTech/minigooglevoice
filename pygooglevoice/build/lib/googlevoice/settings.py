DEFAULT_CONFIG = """
[auth]
# Google Account email address (one associated w/ your Voice account)
email=

# Raw password used or login
password=

# Optional 2-step authentication key (as provided by Google)
smsKey=

[gvoice]
# Number to place calls from (eg, your google voice number)
forwardingNumber=

# Default phoneType for your forwardingNumber as defined below
#  1 - Home
#  2 - Mobile
#  3 - Work
#  7 - Gizmo
#  9 - Googletalk
phoneType=2
"""

TYPES = {
    0: 'missed',
    1: 'received',
    2: 'voicemail',
    4: 'recorded',
    7: 'placed',
    10: 'sms.received',
    11: 'sms.sent'
}

DEBUG = False

LOGIN = 'https://accounts.google.com/ServiceLogin?continue=https://www.google.com/voice&rip=1&nojavascript=1&followup=https://www.google.com/voice&service=grandcentral&ltmpl=open&rip=1&flowName=GlifWebSignIn&flowEntry=Identifier'
#LOGIN = 'https://accounts.google.com/ServiceLogin?continue=https://www.google.com/voice&rip=1&nojavascript=1&followup=https://www.google.com/voice&service=grandcentral&ltmpl=open&flowName=GlifWebSignIn&flowEntry=Identifier'

LOGIN_POST = 'https://accounts.google.com/signin/challenge/sl/password?service=grandcentral&continue=https://www.google.com/voice/redirection/voice&followup=https://www.google.com/voice&ltmpl=open'

USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36'

SMSAUTH = 'https://accounts.google.com/SmsAuth'
FEEDS = ('inbox', 'starred', 'all', 'history', 'spam', 'trash', 'voicemail', 'sms',
        'recorded', 'placed', 'received', 'missed')

BASE = 'https://www.google.com/voice/b/0/'
LOGOUT = 'https://www.google.com/voice/account/signout'
INBOX = BASE + '#inbox'
HISTORY = BASE + '#history'
CALL = BASE + 'call/connect/'
CANCEL = BASE + 'call/cancel/'
DEFAULT_FORWARD = BASE + 'settings/editDefaultForwarding/'
FORWARD = BASE + 'settings/editForwarding/'
SMS_FORWARD = BASE + 'settings/editForwardingSms/'
VOICEMAILNOTIFY = BASE + 'settings/editVoicemailSms/'
ADDNOTE = BASE + 'inbox/savenote/'
DELETENOTE = BASE + 'inbox/deletenote/'
DELETE = BASE + 'inbox/deleteMessages/'
ARCHIVE = BASE + 'inbox/archiveMessages/'
MARK = BASE + 'inbox/mark/'
STAR = BASE + 'inbox/star/'
BLOCK = BASE + 'inbox/block/'
SMS = BASE + 'sms/send/'
DOWNLOAD = BASE + 'media/send_voicemail/'
BALANCE = BASE + 'settings/billingcredit/'

XML_SEARCH = BASE + 'inbox/search/'
XML_CONTACTS = BASE + 'contacts/'
XML_RECENT = BASE + 'inbox/recent/'
XML_MESSAGE = BASE + 'inbox/message/'
XML_INBOX = XML_RECENT + 'inbox/'
XML_STARRED = XML_RECENT + 'starred/'
XML_ALL = XML_RECENT + 'all/'
XML_HISTORY = XML_RECENT + 'history/'
XML_SPAM = XML_RECENT + 'spam/'
XML_TRASH = XML_RECENT + 'trash/'
XML_VOICEMAIL = XML_RECENT + 'voicemail/'
XML_SMS = XML_RECENT + 'sms/'
XML_RECORDED = XML_RECENT + 'recorded/'
XML_PLACED = XML_RECENT + 'placed/'
XML_RECEIVED = XML_RECENT + 'received/'
XML_MISSED = XML_RECENT + 'missed/'
