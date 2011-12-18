 # -*- coding: UTF-8 -*-
import sys
import serial
from time import sleep
from mx.DateTime import now

TIME_BETWEEN_AT = 0.2

class Atcom:

##
# Brief: Set global parameters that will be 
#		used by the most part of the functions.
##
	def __init__(self, wport="/dev/ttyS0", bitrate="115200", logname="at.log", testing_sleep_time=0):
		self.wport = wport
		self.bitrate = bitrate
		self.logname = logname
		self.mtype = mtype
		self.testing_sleep_time = testing_sleep_time
		self.serial = ''
##
# Brief: Save some system messages in the specified file.
# Param: msg The content to be registered.
# Param: tm When the message was registered.
# Return: Since this function do not is performing any crucial 
#	action, does not need a return value to be verified.
##
	def log (self, msg, tm):
		try:
			# Create if does not exist, or opening in append text mode #
			file = open(self.logname, "a")
		except:
			print "\n>An error occurred when trying opening the file."
			print ">Exiting now...\n"
			self.serial.close()
			sys.exit(-1)
		try:
			file.write("\n" + tm + "\n" + msg)
			file.close()
			return
		except:
			print "\n>An error occurred when trying accessing the log file."
			print ">Exiting now...\n"
			self.serial.close()
			file.close()
			sys.exit(-1)
##
# Brief: Open a serial port to communicate with the module.
##
	def open_port (self):
		try:
			self.serial = serial.Serial(self.wport, self.bitrate, timeout=1)
			return
		except IOError, emsg:
			print "\n>An error occurred when opening %s port with %s of bitrate." % (self.wport, self.bitrate)
			print emsg
			print ">Exiting now...\n"
			sys.exit(-1)
##
# Brief: Close the communication serial port.
##
	def close_port(self):
		try:
			self.serial.close()
			return
		except IOError, emsg:
			print "Attempt to close the serial port failed."
			sys.exit(-1)
##
# Brief: Read content of serial buffer.
# Return: The data of the serial buffer if exist.
##
	def read (self):
		try:
			msg = ""
			while(self.serial.inWaiting() > 0):
				msg += self.serial.readline()
			return msg
		except:
			print "\n>An error occurred while reading the serial buffer."
			print ">Exiting now...\n"
			self.serial.close()
			sys.exit(-1)
##
# Brief: Send content in serial buffer.
# Param: msg The content to be sent.
##
	def send (self, msg):
		
		try:
			#print ">Sending %s" % msg
			self.serial.write(msg+'\r')
			sleep(TIME_BETWEEN_AT)
		except:
			print "\n>An error occurred when sending data in the serial buffer."
			print ">Exiting now...\n"
			self.serial.close()
			sys.exit(-1)

		self.log("Command: %s\n" % msg, "%s" % now())	
		# get response # * the answer is taking too long
		# uncomment the lines bellow to register the module answers
		#r_msg = self.read()
		#self.log("Answer:\n %s\n" % r_msg, "%s" % now())
		return
##
# Brief: Print the chosen parameters to be used.
##
	def info (self):
		print "Valores utilizados: %s %s %s %s %s" % (self.wport, self.bitrate, self.logname, self.mtype, self.testing_sleep_time)
		return
	
###########################
# Specific functions	  #
###########################
##
# Brief: Send initializing commands to the module
##
	def initModule (self):
		pass

