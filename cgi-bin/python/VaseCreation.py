#!/usr/bin/env python
'''
Created on Apr 24, 2014

@author: Britton Horn
'''
import sys
import random
import cv2
import numpy
from xml.etree.ElementTree import Element, SubElement, tostring
import MySQLdb as mdb
from Vase import Vase
import cgi, cgitb 


def getRandomSide():
    if random.random() > 0.2:
        return "BOTH"
    elif random.random() > 0.5:
        return "LEFT"
    else:
        return "RIGHT"
    
def spline_4p( t, p_1, p0, p1, p2 ):
    return (
          t*((2-t)*t - 1)   * p_1
        + (t*t*(3*t - 5) + 2) * p0
        + t*((4 - 3*t)*t + 1) * p1
        + (t-1)*t*t         * p2 ) / 2.0
        
def writeToDB(upload_id, xml):
    #print "writing to DB"
    con = mdb.connect(read_default_file='./.VivConnection.mysql');
    with con:
        cur = con.cursor()
        
        cur.execute("INSERT INTO vase(vase_id, xml_data) VALUES(1, %s)", xml)
        
    #print "finished writing to DB"
        
def createAndWriteXML(upload_id, writeToDB):
    completeVase = Element('vase')
    
    for i in range(len(finalRight)):
        pointpair = SubElement(completeVase, 'pointpair')
        left = SubElement(pointpair, 'left')
        right = SubElement(pointpair, 'right')
        
        t = SubElement(pointpair, 't')
        
        xRight = SubElement(right, 'x')
        xLeft = SubElement(left, 'x')
        yRight = SubElement(right, 'y')
        yLeft = SubElement(left, 'y')
        
        t.text = "0"
        xRight.text = str(finalRight[i][0])
        xLeft.text = str(finalLeft[i][0])
        yRight.text = str(finalRight[i][1])
        yLeft.text = str(finalLeft[i][1])
    
    if writeToDB == 'True':
        writeToDB(upload_id, tostring(completeVase))
    #print "Content-type: text/html"
    #print
    print tostring(completeVase)
    #return tostring(completeVase)
        
def draw():
    img = numpy.zeros((200,200,3), numpy.uint8)
    for i in range(len(finalRight)-1):
        cv2.line(img, (int(finalRight[i][0]+50),int(200-finalRight[i][1])), (int(finalRight[i+1][0]+50),int(200-finalRight[i+1][1])), (255,255,255))
    for i in range(len(finalLeft)-1):
        cv2.line(img, (int(finalLeft[i][0]+50),int(200-finalLeft[i][1])), (int(finalLeft[i+1][0]+50),int(200-finalLeft[i+1][1])), (255,255,255))
        
    cv2.imshow('rnd vase', img)
    cv2.waitKey(0)
    cv2.destroyAllWindows()

cgitb.enable()  # for troubleshooting

#the cgi library gets vars from html
data = cgi.FieldStorage()
#this is the actual output
print "Content-Type: text/html\n"
#print "The foo data is: " + data["upload_id"].value
#print "<br />"
#print data

v = Vase()

if random.random() > 0.2:
    v.squeeze(random.randint(2, 10)*2, random.randint(3, 5)*2, getRandomSide())
    if random.random() > 0.5:
        v.squeeze(random.randint(2, 10)*2, random.randint(3, 5)*2, getRandomSide())
        
if random.random() > 0.2:
    v.pull(random.randint(2, 10)*2, random.randint(3, 5)*2, getRandomSide())
    
if random.random() > 0.5:
    v.shorten(random.uniform(0.75, 0.9), getRandomSide())
    
finalRight = []
finalLeft = []
finalRight.append(v.rightedge[0])
finalLeft.append(v.leftedge[0])
for j in range(1, len(v.leftedge) - 2):
    for t in range( 10 ):  # t: 0 .1 .2 .. .9
        p = spline_4p( t/10.0, v.leftedge[j-1], v.leftedge[j], v.leftedge[j+1], v.leftedge[j+2] )
        # add p to list of points
        finalLeft.append(p)
        #print "p: ", numpy.round(p)

for j in range(1, len(v.rightedge) - 2):
    for t in range( 10 ):  # t: 0 .1 .2 .. .9
        p = spline_4p( t/10.0, v.rightedge[j-1], v.rightedge[j], v.rightedge[j+1], v.rightedge[j+2] )
        # add p to list of points
        finalRight.append(p)
        #print "p: ", numpy.round(p)
finalRight.append(v.rightedge[len(v.rightedge)-1])
finalLeft.append(v.leftedge[len(v.leftedge)-1])
#print finalRight
#print "============"
#print finalLeft
createAndWriteXML(0, 'False')
#draw()
