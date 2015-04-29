#!/usr/bin/env python
'''
Created on Jan 1, 2015

@author: Britton Horn
'''
import ImportImage
import AffectAnalysis as aa
import WebEvolveVase
from xml.etree.ElementTree import Element, SubElement, tostring
#import time
#import sys
#import CreateVase
import Utilities
#import cv2
import cgi, cgitb

cgitb.enable()
data = cgi.FieldStorage()

chosen_pic = -1
img_data = -1

if data.has_key("chosen_pic"):
    chosen_pic = data["chosen_pic"].value
if data.has_key("image"):
    img_data = data["image"].value
print "Content-Type: text/html\n"
#data = r'C:\Misc\AnalysisPics\Bride.jpg'
session_id = data["session_id"].value
#session_id = 0
#print session_id
#print img_data

#start = time.clock()
#print "importing image" 
if img_data != -1:
    image, image_id = ImportImage.importImageFromData(img_data)
if chosen_pic != -1:
    image, image_id = ImportImage.importImageFromFile('/var/www/html/assets/img/sample%s.jpg' % (chosen_pic), chosen_pic)
#print "finished image import"

activity, warmth, heaviness, hardness = aa.analyzeAffectOfImage(image, None, None)
image.affectValues['activity'] = activity
image.affectValues['warmth'] = warmth
image.affectValues['heaviness'] = heaviness
image.affectValues['hardness'] = hardness
Utilities.writeImageColorDataToDB(image_id, image)
vase, vase_id = WebEvolveVase.evolveVase(activity, heaviness, warmth, hardness, image_id)
#print "image affect:",image.affectValues
returndata = Element('mydata')   
vase_id_xml = SubElement(returndata, 'vaseid')
image_id_xml = SubElement(returndata, 'imageid')
vase_id_xml.text = str(vase_id)
image_id_xml.text = str(image_id)
print tostring(returndata)
#print "<returndata><vaseid>%d</vaseid><imageid>%d</imageid></returndata>" % (vase_id, image_id)
