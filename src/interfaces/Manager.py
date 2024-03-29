# -*-coding:utf-8-*-
import socket
from threading import Thread
from mx.DateTime import now
from libs.log.slog import slog
from libs.defines.defines import *
from libs.alarm.alarm import alarm
from libs.shared.shared import shared

class Manager:

	def __init__(self, log_path, alarm_log_path):

		self.log = slog(log_path)
		self.alarm = alarm(alarm_log_path)
		self.shared = shared()
##
# Brief: Clean all data from log file.
##
	def actionCleanLog(self, path):
	
		ret = self.log.CLEAN(path)
	
		if ret == OK:
			self.log.LOG(LOG_INFO, "manager", "The log %s has been reseted." % path)
		else:
			self.log.LOG(LOG_ERROR, "manager", "Attempt to clean the log failed.")
##
# Brief: Schedule an alarm.
##
	def actionScheduleAlarm(self, cmsg, dbcom):
	
		sms_dict = self.alarm.retrieveData(cmsg)

		activity = ACTIVE

		if sms_dict == NOTFOUND:
			self.log.LOG(LOG_ERROR, "sms.actionScheduleAlarm()", "TAGs are missing in the requisition to schedule an alarm. Aborting schedule.")
			return "NOTFOUND"

		blow = self.shared.mountTime(sms_dict[DATA_BLOW])


		if blow == INVALID:
			return "INVALID"

		elif blow < now() or blow == now():
			activity = FAILED

		ret = dbcom.registerSMS(sms_dict[DATA_ORG], sms_dict[DATA_EXT+"0"], sms_dict[DATA_BLOW], sms_dict[DATA_OPER], sms_dict[DATA_MSG], activity)

		if ret == OK and activity == ACTIVE:
			alarm_thread = Thread(target=self.alarm.launch, args=(blow,))
			alarm_thread.start()
			self.log.LOG(LOG_INFO, "sms", "New alarm thread has been started.")
			return "OK"

		elif ret == NOTFOUND:
			return "NOTFOUND"

		elif activity == FAILED:
			return "INVALID"

		else:
			return "ERROR"

