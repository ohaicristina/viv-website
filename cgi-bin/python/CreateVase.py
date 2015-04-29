#!/usr/bin/env python
'''
Created on Apr 24, 2014

@author: Britton Horn
'''
import sys
import random
import cv2
import numpy as np
from xml.etree.ElementTree import Element, SubElement, tostring
import MySQLdb as mdb
from Vase import Vase
import cgi, cgitb 
import Utilities as util

def getRandomSide():
    if random.random() > 0.2:
        return "BOTH"
    elif random.random() > 0.5:
        return "LEFT"
    else:
        return "RIGHT"
        
def writeToDB(upload_id, xml, basePts):
    con = mdb.connect(read_default_file='./.VivConnection.mysql');
    with con:
        cur = con.cursor()
        cur.execute("INSERT INTO vase(vase_id, xml_data, num_base_pts) VALUES(%s, %s, %s);", (upload_id, xml, basePts))
        
    #print "finished writing to DB"
        
def createAndWriteXML(upload_id, writeToDBValue, finalRight, finalLeft, basePts):
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
    
    if writeToDBValue == 'True':
        writeToDB(upload_id, tostring(completeVase), basePts)
    print tostring(completeVase)
    #return tostring(completeVase)
    
def createAndSaveWebVase(v, img_id):
    #print "saving vase"
    completeVase = Element('vase')
    
    for i in range(len(v.finalRight)):
        pointpair = SubElement(completeVase, 'pointpair')
        left = SubElement(pointpair, 'left')
        right = SubElement(pointpair, 'right')
        
        t = SubElement(pointpair, 't')
        
        xRight = SubElement(right, 'x')
        xLeft = SubElement(left, 'x')
        yRight = SubElement(right, 'y')
        yLeft = SubElement(left, 'y')
        
        t.text = "0"
        xRight.text = str(v.finalRight[i][0])
        xLeft.text = str(v.finalLeft[i][0])
        yRight.text = str(v.finalRight[i][1])
        yLeft.text = str(v.finalLeft[i][1])

    insert_id = saveWebVase(tostring(completeVase), v.basePts, v.leftedgeX, v.rightedgeX, v.DNA, v.initHeight, v.numVertices, img_id, v.affectValues)
    return insert_id
    #return tostring(completeVase)
    
def generateDNAStringForDB(dna):
    #print dna
    dnaString = ""
    for op in dna:
        if len(op) == 3:
            #print (op[0],op[1],op[2])
            dnaString += "%s,%d,%s&" % (op[0],op[1],op[2])
        else:
            #print (op[0],op[1],op[2],op[3])
            dnaString += "%s,%d,%d,%s&" % (op[0],op[1],op[2],op[3])
    #print dnaString
    return dnaString

def saveWebVase(xml, basePts, leftX, rightX, dna, height, numVerts, img_id, affectValues):
    con = mdb.connect(read_default_file='./.VivConnection.mysql');

    #create DNA string
    dnaString = generateDNAStringForDB(dna)
    
    with con:
        cur = con.cursor()
        cur.execute("INSERT INTO vase(xml_data, num_base_pts, left_edge_x, right_edge_x, init_height, num_vertices, dna, image_id, activity_score, weight_score, warmth_score, hardness_score) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);", 
                    (xml, basePts, leftX, rightX, height, numVerts, dnaString, img_id, affectValues['activity'], affectValues['heaviness'], affectValues['warmth'], affectValues['hardness']))
        last_id = cur.lastrowid
        con.commit
    return last_id
        
def draw(finalRight, finalLeft):
    img = np.zeros((200,200,3), np.uint8)
    for i in range(len(finalRight)-1):
        cv2.line(img, (int(finalRight[i][0]+50),int(200-finalRight[i][1])), (int(finalRight[i+1][0]+50),int(200-finalRight[i+1][1])), (255,255,255))
    for i in range(len(finalLeft)-1):
        cv2.line(img, (int(finalLeft[i][0]+50),int(200-finalLeft[i][1])), (int(finalLeft[i+1][0]+50),int(200-finalLeft[i+1][1])), (255,255,255))
        
    cv2.imshow('rnd vase', img)
    cv2.waitKey(0)
    cv2.destroyAllWindows()
    
def drawAnalyzedVase(v):
    vase = util.splineVase(v)
    img = np.zeros((200,200,3), np.uint8)
    for i in range(len(vase.finalRight)-1):
        cv2.line(img, (int(vase.finalRight[i][0]+50),int(200-vase.finalRight[i][1])), (int(vase.finalRight[i+1][0]+50),int(200-vase.finalRight[i+1][1])), (255,255,255))
    for i in range(len(vase.finalLeft)-1):
        cv2.line(img, (int(vase.finalLeft[i][0]+50),int(200-vase.finalLeft[i][1])), (int(vase.finalLeft[i+1][0]+50),int(200-vase.finalLeft[i+1][1])), (255,255,255))
    
    print "min:",int(vase.leftedge[vase.leftMinWidthIndex][0]+50),int(200-vase.leftedge[vase.leftMinWidthIndex][1]),int(vase.rightedge[vase.rightMinWidthIndex][0]+50),int(200-vase.rightedge[vase.rightMinWidthIndex][1])
    cv2.line(img, (int(vase.leftedge[vase.leftMinWidthIndex][0]+50),int(200-vase.leftedge[vase.leftMinWidthIndex][1])), (int(vase.rightedge[vase.rightMinWidthIndex][0]+50),int(200-vase.rightedge[vase.rightMinWidthIndex][1])), (255, 255, 0))
    cv2.line(img, (int(vase.leftedge[vase.leftMaxWidthIndex][0]+50),int(200-vase.leftedge[vase.leftMaxWidthIndex][1])), (int(vase.rightedge[vase.rightMaxWidthIndex][0]+50),int(200-vase.rightedge[vase.rightMaxWidthIndex][1])), (255, 255, 0))
    cv2.circle(img,(int(vase.centerOfMassX + 50),int(200 - vase.centerOfMassY)),2,(0,255,255),3)
    
    cv2.imshow('rnd vase', img)
    cv2.waitKey(0)
    cv2.destroyAllWindows()
    
def drawImportedVase(finalLeft,finalRight):
    img = np.zeros((200,200,3), np.uint8)
    for i in range(len(finalRight)-1):
        cv2.line(img, (int(finalRight[i][0]+50),int(200-finalRight[i][1])), (int(finalRight[i+1][0]+50),int(200-finalRight[i+1][1])), (255,255,255))
    for i in range(len(finalLeft)-1):
        cv2.line(img, (int(finalLeft[i][0]+50),int(200-finalLeft[i][1])), (int(finalLeft[i+1][0]+50),int(200-finalLeft[i+1][1])), (255,255,255))
    
    cv2.imshow('rnd vase', img)
    cv2.waitKey(0)
    cv2.destroyAllWindows()
    
def createImageWithText(finalRight, finalLeft, text):
    scale = 2
    window_size = 180 * scale

    img = np.zeros((window_size,window_size,3), np.uint8)
    for i in range(len(finalRight)-1):
        cv2.line(img, (int(finalRight[i][0]*scale+50*scale),int(window_size-finalRight[i][1]*scale)), 
                 (int(finalRight[i+1][0]*scale+50*scale),int(window_size-finalRight[i+1][1]*scale)), (255,255,255))
    for i in range(len(finalLeft)-1):
        cv2.line(img, (int(finalLeft[i][0]*scale+50*scale),int(window_size-finalLeft[i][1]*scale)), 
                 (int(finalLeft[i+1][0]*scale+50*scale),int(window_size-finalLeft[i+1][1]*scale)), (255,255,255))
    
    cv2.putText(img, str(text), (10,20), cv2.FONT_HERSHEY_PLAIN, 1, (255,255,255))
    
    return img

#cgitb.enable()  # for troubleshooting

#the cgi library gets vars from html
#data = cgi.FieldStorage()
#this is the actual output
#print "Content-Type: text/html\n"
#print "The foo data is: " + data["upload_id"].value
#print "<br />"
#print data

def createVase():
    v = Vase()
    
    if random.random() > 0.2:
        v.squeeze(random.randint(2, 10)*2, random.randint(2, 15), getRandomSide())
        if random.random() > 0.5:
            v.squeeze(random.randint(2, 10)*2, random.randint(2, 15), getRandomSide())
            
    if random.random() > 0.2:
        v.pull(random.randint(2, 10)*2, random.randint(2, 15), getRandomSide())
        
    if random.random() > 0.5:
        v.shorten(random.uniform(0.75, 0.9), getRandomSide())
    '''
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
    '''
    #print finalRight
    #print "============"
    #print finalLeft
    #createAndWriteXML(0, 'False', finalRight, finalLeft) 
    #draw(finalRight, finalLeft)
    #v.finalLeft = finalLeft
    #v.finalRight = finalRight
    v.calculateVaseMetrics()
    return v

def createVaseFromDNA(vase):
    v = Vase()
    v.numVertices = vase.numVertices
    v.initHeight = vase.initHeight
    v.leftedgeX = vase.leftedgeX
    v.rightedgeX = vase.rightedgeX
    v.parentDNA = vase.parentDNA[:]
    v.basePts = vase.basePts
    
    v.leftedge = np.zeros((v.numVertices,2), np.float32)
    #print self.leftedge.shape
    v.rightedge = np.zeros((v.numVertices,2), np.float32)
    for i in range(v.numVertices):
        v.leftedge[i] = [v.leftedgeX, i*v.initHeight]
        v.rightedge[i] = [v.rightedgeX, i*v.initHeight]
    
    for i in range(len(vase.DNA)):
        if vase.DNA[i][0] == 'squeeze':
            v.squeeze(vase.DNA[i][1], vase.DNA[i][2], vase.DNA[i][3])
        if vase.DNA[i][0] == 'shorten':
            v.shorten(vase.DNA[i][1], vase.DNA[i][2])
    '''    
    finalRight = []
    finalLeft = []
    finalRight.append(v.rightedge[0])
    finalLeft.append(v.leftedge[0])
    for j in range(1, len(v.leftedge) - 2):
        for t in range( 10 ):
            p = spline_4p( t/10.0, v.leftedge[j-1], v.leftedge[j], v.leftedge[j+1], v.leftedge[j+2] )
            finalLeft.append(p)

    
    for j in range(1, len(v.rightedge) - 2):
        for t in range( 10 ):
            p = spline_4p( t/10.0, v.rightedge[j-1], v.rightedge[j], v.rightedge[j+1], v.rightedge[j+2] )
            finalRight.append(p)
            
    finalRight.append(v.rightedge[len(v.rightedge)-1])
    finalLeft.append(v.leftedge[len(v.leftedge)-1])
    
    #v.finalLeft = finalLeft
    #v.finalRight = finalRight
    '''
    v.calculateVaseMetrics()
    
    return v

def createVaseFromDB(dna, basePts, numVertices, initHeight, leftedgeX, rightedgeX):
    v = Vase()
    v.numVertices = numVertices
    v.initHeight = initHeight
    v.leftedgeX = leftedgeX
    v.rightedgeX = rightedgeX
    v.basePts = basePts
    
    v.leftedge = np.zeros((v.numVertices,2), np.float32)
    #print self.leftedge.shape
    v.rightedge = np.zeros((v.numVertices,2), np.float32)
    for i in range(v.numVertices):
        v.leftedge[i] = [v.leftedgeX, i*v.initHeight]
        v.rightedge[i] = [v.rightedgeX, i*v.initHeight]
    
    for i in range(len(dna)):
        if dna[i][0] == 'squeeze':
            v.squeeze(dna[i][1], dna[i][2], dna[i][3])
        if dna[i][0] == 'shorten':
            v.shorten(dna[i][1], dna[i][2])
    v.calculateVaseMetrics()
    return v
