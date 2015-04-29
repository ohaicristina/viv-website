#!/usr/bin/env python
'''
Created on Jan 1, 2015

@author: Britton Horn
'''
import WebEvolveVase
#import time
import sys
#import CreateVase
import Utilities
#import cv2
import cgi, cgitb

#cgitb.enable()
data = cgi.FieldStorage()
print "Content-Type: text/html\n"
#data = "Bride.jpg"
#session_id = data["session_id"].value
#print session_id
#img_id = data["image_id"].value
#print img_data

#get affect values from db with image_id
img_id = 4
activity, weight, warmth, hardness = Utilities.getImageAffectFromDB(img_id)
#print activity, weight, warmth, hardness
vase, vase_id = WebEvolveVase.evolveVase(activity, weight, warmth, hardness, img_id)
#evolve vases
#affect difference
#save off vase
print vase_id
