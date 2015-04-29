#!/usr/bin/env python
'''
Created on January 14, 2014

@author: Britton Horn
'''

import sys
import Utilities
import cgi, cgitb
import WebEvolveVase
import CreateVase
import AffectAnalysis
import numpy as np
from xml.etree.ElementTree import Element, SubElement, tostring

data = cgi.FieldStorage()
print "Content-Type: text/html\n"
image_id = data["image"].value
vase_id = data["vase"].value
affect_changes = data["affect_string"].value

fin, basePts, numVerts, height, leftX, rightX, activity, weight, warmth, hardness, image_id = Utilities.getVaseFromDB(vase_id)
vase = CreateVase.createVaseFromDB(fin, basePts, numVerts, height, leftX, rightX)
#print vase.basePts, vase.initHeight, vase.leftedgeX, vase.rightedgeX, vase.numVertices, vase.DNA
#print activity, weight, warmth, hardness

#Add/Remove affect for the passed values
#affect_changes = "1121"
if affect_changes[0] == "0":
    activity -= 0.2
    if activity < 0:
        activity = 0
if affect_changes[0] == "2":
    activity += 0.2
    if activity > 1:
        activity = 1

if affect_changes[1] == "0":
    weight -= 0.2
    if weight < 0:
        weight = 0
if affect_changes[1] == "2":
    weight += 0.2
    if weight > 1:
        weight = 1

if affect_changes[2] == "0":
    warmth -= 0.2
    if warmth < 0:
        warmth = 0
if affect_changes[2] == "2":
    warmth += 0.2
    if warmth > 1:
        warmth = 1

if affect_changes[3] == "0":
    hardness -= 0.2
    if hardness < 0:
        hardness = 0
if affect_changes[3] == "2":
    hardness += 0.2
    if hardness > 1:
        hardness = 1

#print activity, weight, warmth, hardness


candidate_vases = []
affect_differences = []
for i in range(1000):
    candidate_vases.append(WebEvolveVase.mutate(vase))
    #print i,candidate_vases[i].DNA
for i in range(1000):
    AffectAnalysis.analyzeAffectOfVase(candidate_vases[i]) 
    affect_differences.append(Utilities.affectDifferenceFromValues(candidate_vases[i], activity, weight, warmth, hardness))
       
sorted_indices = np.argsort(affect_differences)

#print sorted_indices[0]
newVase = Utilities.splineVase(candidate_vases[sorted_indices[0]])

newvase_id = CreateVase.createAndSaveWebVase(newVase, image_id)

#print "newvase id: ", newvase_id
#print newVase.basePts, newVase.initHeight, newVase.leftedgeX, newVase.rightedgeX, newVase.numVertices, newVase.DNA
#print newVase.affectValues["activity"],  newVase.affectValues["heaviness"],  newVase.affectValues["warmth"],  newVase.affectValues["hardness"]

#vase = Utilities.splineVase(vase)
#CreateVase.draw(vase.finalLeft, vase.finalRight)
#CreateVase.draw(newVase.finalLeft, newVase.finalRight)
returndata = Element('returndata')   
vase_id_xml = SubElement(returndata, 'vaseid')
image_id_xml = SubElement(returndata, 'imageid')
vase_id_xml.text = str(newvase_id)
image_id_xml.text = str(image_id)
print tostring(returndata)
