import time
from mx.DateTime import now
from libs.defines.defines import *
from libs.shared.shared import *
from libs.log.slog import slog


class alarm:
##
# Brief: Initialing objects to processing.
##
	def __init__(self, log_path):
		self.log = slog(log_path)
		self.shared = shared()
##
# Brief: The alarm function will need do calculation about the time to sleep, and
# when he wake up will need to send data to main system, asking for the alarm to blow.
##
	#def launch(self, orig, destination, content, blow):
	def launch(self, blow):

		try:
			sleep_time = int(blow - now())

			self.log.LOG(LOG_INFO, "alarm", "New alarm has been scheduled. Blow date/time in %s. It's take %d seconds from now." % (blow, sleep_time))

			time.sleep(sleep_time)

			self.log.LOG(LOG_INFO, "alarm", "THE ALARM HAS EXPLODED!!!.")

		except:
			self.log.LOG(LOG_CRITICAL, "alarm.launch()", "The alarm thread has a problem and will be aborted.")	
	# open a connection with the main system #
	# ask the system to send the content #
	# and.. die =]
	
##
# Brief: Mounts a dictionary with the content of 
#	TAGs in the data package.
# Param: cmsg The data package.
# Return: The data dictionary if all the data is OK;
#	ERROR if something is missing.
##
	def retrieveData(self, cmsg):

		sms_dict = {}
		# Originator #
		ret = self.shared.splitTag(cmsg, TAG_FROM)
		if ret == NOTFOUND:
			return NOTFOUND

		else:
			sms_dict[DATA_ORG] = ret

		ret = self.shared.splitTag(cmsg, TAG_CONTENT)
		# Message #
		if ret == NOTFOUND:
			return NOTFOUND

		else:
			sms_dict[DATA_MSG] = ret

		ret = self.shared.splitTag(cmsg, TAG_BLOW)
		# Blow #
		if ret == NOTFOUND:
			return NOTFOUND
		else:
			sms_dict[DATA_BLOW] = ret

		ret = int(self.shared.splitTag(cmsg, TAG_INFO))
		# Operator #
		if ret ==  NOTFOUND:
			return NOTFOUND
		else:
			sms_dict[DATA_OPER] = ret

		ret = self.shared.splitTag(cmsg, TAG_HOWMANY)
		# How many destinations #
		if ret == NOTFOUND:
			return NOTFOUND
		else:
			sms_dict[DATA_DESTN] = ret

		for index in range (0, sms_dict[DATA_DESTN]):

			ret = self.shared.splitTag(cmsg, TAG_PART, index)
			if ret == NOTFOUND:
				return NOTFOUND
			else:
				sms_dict[DATA_EXT + "%d" % index] = ret

		return sms_dict

