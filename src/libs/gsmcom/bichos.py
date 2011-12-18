# -*- coding: utf-8 -*-
from Animal import Animal
import sys

class Cao(Animal):

	def cor(self):
		return "amarelo"

	def comida(self):
		return "racao"

class Gato(Animal):

	def cor(self):
		return "preto"

	def comida(self):
		return "camundongo"

	def raca(self):
		return "gato"


if len(sys.argv) > 1:
	a = Cao()
else:
	a = Gato()

print a.raca()
print a.cor()
print a.comida()
