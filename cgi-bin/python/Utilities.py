#!/usr/bin/env python
'''
Created on May 21, 2014

@author: Britton Horn
'''
import math
import numpy
from collections import defaultdict
from bisect import bisect_left
import MySQLdb as mdb

def determineLinearity(line):
    #print line
    eps = .1
    
    linearPointCount = 0
    total = 0
    
    corners, angles = findCorners(line)
    #print corners
    for i in range(1,len(corners)):
        for k in range(corners[i-1],corners[i]):
            if angles[k] > 180 - eps:
                linearPointCount = linearPointCount + 1
            total = total + 1
            
    #print "linear:",linearPointCount
    #print "total:",total
    #print "ratio:", linearPointCount / float(total)
    
    return linearPointCount / float(total)
    #find variation of angles from point to corner, closer to 180 = linear
        

    #print "max angle:", maxAngle
    #print "min angle:", minAngle
            
def determineAngleBetween(vec1, vec2):
    dot = vec1[0]*vec2[0] + vec1[1]*vec2[1]
    
    vec1_length = math.sqrt(vec1[0]**2 + vec1[1]**2)
    vec2_length = math.sqrt(vec2[0]**2 + vec2[1]**2)
    
    cos_angle = dot/(vec1_length * vec2_length)
    if cos_angle < -1.0:
        cos_angle = -1.0
    if cos_angle > 1.0:
        cos_angle = 1.0
    angle = math.acos(cos_angle)
    angle = angle * (180 / math.pi)
    
    return angle

def findCorners(line):
    angleThreshold = 170
    cornerThreshold = 150
    eps = .001
    
    maxAngle = 0
    minAngle = 1000
    
    pts_to_remove = []
    corner_estimates = []
    corners = []
    corner_estimates.append(0)
    
    angles = defaultdict(list)
    
    #look ahead 5 pts to calculate angle
    lookahead = 4
    for i in range(lookahead+1, len(line)-lookahead-1):
        #print "starting", i
        #print "i-lookahead-2:",i-lookahead-2
        #print "angles",angles
        #find angle for point before and point after
        if i == lookahead+1 or i == lookahead+2:
            vec1 = [line[i-1-lookahead][0]-line[i-1][0], line[i-1-lookahead][1]-line[i-1][1]]
            vec2 = [line[i-1+lookahead][0]-line[i-1][0], line[i-1+lookahead][1]-line[i-1][1]]
            
            angle1 = determineAngleBetween(vec1, vec2)
            angles[i-1] = angle1
        else:
            angle1 = angles[i-1]
        
        vec1 = [line[i+1-lookahead][0]-line[i+1][0], line[i+1-lookahead][1]-line[i+1][1]]
        vec2 = [line[i+1+lookahead][0]-line[i+1][0], line[i+1+lookahead][1]-line[i+1][1]]
        
        angle2 = determineAngleBetween(vec1, vec2)
        angles[i+1] = angle2
        
        #print "angle 1:", angle1
        #print "angle 2:", angle2
        #print "curve estimate:", angle2 - angle1
        
        if angle2 < cornerThreshold - eps:
            corners.append(i+lookahead+1)
            
        if angle2 - angle1 > 20 or angle2 - angle1 < -20:
            corner_estimates.append(i)

        #print "finished:", i
        '''
        if angle > maxAngle:
            maxAngle = angle
        if angle < minAngle:
            minAngle = angle
        #print angle
        
        if angle < angleThreshold - threshold:
            #print "angle too small"
            pts_to_remove.append(i)
        '''  
    corner_estimates.append(len(line))  
    #print "initial pts:", len(line)
    #print "removed pts:", len(pts_to_remove)
    #print "corners:",len(corners)
    #print "curve values:",corner_estimates
    #print "curve number:",len(corner_estimates)
    
    return corner_estimates, angles

# range from 0 to 1 when using maxDifference
def determineSymmetry(vase):
    totalXDiff = 0
    maxDiff = 0
    left = vase.finalLeft
    right = vase.finalRight
    
    for i in range(len(left)):
        diff = math.sqrt(abs(abs(left[i][0])-right[i][0])**2 + abs(left[i][1]-right[i][1])**2)
        totalXDiff = totalXDiff + diff
        if diff > maxDiff:
            maxDiff = diff
        
    if maxDiff == 0:
        return 1.0
    symmValue = ((len(right) * maxDiff) - totalXDiff) / (float(len(right) * maxDiff))
    #print "symm value:",symmValue
    #print "avgDiff:",avgDiff
    
    #if avgDiff < 1.0:
        #return 1
        
    # so far, symmValue = 1 means asymm (closer to extreme differences), now need to reverse that
    #return (symmValue - 1) * -1
    return symmValue

def affectDifference(vase, image):
    imageAffects = image.affectValues
    vaseAffects = vase.affectValues
    
    _difference = 0.0
    _total = 0
    
    for key in imageAffects:
        #print "image affect:",imageAffects[i]
        #print "vase affect:",vaseAffects[i]
        #_difference = _difference + math.pow(abs(imageAffects[key]-vaseAffects[key]),2)
        _difference +=abs(imageAffects[key]-vaseAffects[key])
        #print "_difference:",_difference
        
    #print "diff:",_difference
    #print "avg diff:",_difference / float(_total)
        
    #return math.sqrt(_difference)
    return _difference

def affectDifferenceFromValues(vase, activity, weight, warmth, hardness):
    _difference = 0.0
    _total = 0
    
    _difference += abs(activity - vase.affectValues['activity'])
    _difference += abs(weight - vase.affectValues['heaviness'])
    _difference += abs(warmth - vase.affectValues['warmth'])
    _difference += abs(hardness - vase.affectValues['hardness'])
    
    return _difference

def targetedAffectDifference(vase, image, metric):
    imageAffects = image.affectValues
    vaseAffects = vase.affectValues
    return abs(imageAffects[metric]-vaseAffects[metric])
    
def calcColorScores(distinct_colors, dom_colors, most_sal_colors, least_sal_colors):
    activity_sum = 0
    heaviness_sum = 0
    warmth_sum = 0
    hardness_sum = 0
    
    used_colors = []
    used_colors.append(distinct_colors[0])
    
    if len(distinct_colors) > 1:
        #print "more than 1 distinct color"
        if len(most_sal_colors) > 0:
            #print "most sal colors > 0"
            used_colors.append(most_sal_colors[0])
            tmp_colors = removeSimilarColors(used_colors)
            if len(tmp_colors) < 2:
                if len(most_sal_colors) > 1:
                    #print "colors too similar, adding next most sal color"
                    used_colors.append(most_sal_colors[1])
                else:
                    #print "no more most sal colors left, adding distinct color"
                    used_colors.append(distinct_colors[1])
        elif len(least_sal_colors) > 0:
            #print "least sal colors > 0"
            used_colors.append(least_sal_colors[0])
            tmp_colors = removeSimilarColors(used_colors)
            if len(tmp_colors) < 2:
                if len(least_sal_colors) > 1:
                    #print "colors too similar, adding next least sal color"
                    used_colors.append(least_sal_colors[1])
                else:
                    #print "no more least sal colors left, adding distinct color"
                    used_colors.append(distinct_colors[1])
        else:
            #print "just using distinct"
            used_colors.append(distinct_colors[1])
    #print "used colors:",used_colors
    for color in used_colors:
        activity, heaviness, warmth, hardness = getColorScore(color)   
        
        activity_sum += activity
        heaviness_sum += heaviness
        warmth_sum += warmth
        hardness_sum += hardness
        
    activity_avg = activity_sum / float(len(used_colors))
    heaviness_avg = heaviness_sum / float(len(used_colors))
    warmth_avg = warmth_sum / float(len(used_colors))
    hardness_avg = hardness_sum / float(len(used_colors))
    
    #print "activity avg:", activity_avg
    #print "warmth avg:", warmth_avg
    #print "heaviness avg:", heaviness_avg
    #print "hardness avg:", hardness_avg
    
    return activity_avg, warmth_avg, heaviness_avg, hardness_avg

def findClosestInArray(array, value):
    """
    Assumes myList is sorted. Returns index of closest value to value.

    If two numbers are equally close, return the smallest number index.
    """
    pos = bisect_left(array, value)
    if pos == 0:
        return 0
    if pos == len(array):
        return len(array)-1
    before = array[pos - 1]
    after = array[pos]
    if after - value < value - before:
        return pos
    else:
        return pos-1
def splineVase(vase):
    finalRight = []
    finalLeft = []
    finalRight.append(vase.rightedge[0])
    finalLeft.append(vase.leftedge[0])
    for j in range(1, len(vase.leftedge) - 2):
        for t in range( 10 ):
            p = spline_4p( t/10.0, vase.leftedge[j-1], vase.leftedge[j], vase.leftedge[j+1], vase.leftedge[j+2] )
            finalLeft.append(p)

    
    for j in range(1, len(vase.rightedge) - 2):
        for t in range( 10 ):
            p = spline_4p( t/10.0, vase.rightedge[j-1], vase.rightedge[j], vase.rightedge[j+1], vase.rightedge[j+2] )
            finalRight.append(p)
            
    finalRight.append(vase.rightedge[len(vase.rightedge)-1])
    finalLeft.append(vase.leftedge[len(vase.leftedge)-1])
    
    vase.finalRight = finalRight
    vase.finalLeft = finalLeft
    
    return vase
    
def spline_4p( t, p_1, p0, p1, p2 ):
    return (
          t*((2-t)*t - 1)   * p_1
        + (t*t*(3*t - 5) + 2) * p0
        + t*((4 - 3*t)*t + 1) * p1
        + (t-1)*t*t         * p2 ) / 2.0
        
def getColorScore(color):
    #max/min scores for each component
    max_activity = 2.94911081637
    min_activity = -1.08706170014
    max_heaviness = 2.9
    min_heaviness = -2.1
    max_warmth = 2.35143012179
    min_warmth = -1.65888088711
    #huge variance in hardness at the top of the spectrum, setting to (90,90,90)
    max_hardness = 2.84337366538
    min_hardness = -1.30618131742
    
    #print "color:",color
    hue = math.atan2(color[2], color[1]) * (180 / math.pi)
    #print "hue:",hue
    chroma = math.sqrt(color[1]**2 + color[2]**2)
    #print "chroma:",chroma
    
    activity = -1.1 + 0.03 * math.sqrt(chroma ** 2 + (color[0] - 50)**2)
    #activity = -2.1 + .06 * math.sqrt((color[0] - 50)**2 + (color[1] - 3)**2 + ((color[2] - 17)/1.4)**2)
    heaviness = -2.1 + 0.05 * (100 - color[0])

    #weight = -1.8 + .04 * (100 - color[0]) + .45 * math.cos(math.radians(hue - 100))
    warmth = -.5 + .02 * (chroma ** 1.07) * math.cos(math.radians(hue - 50))     
    hardness = 11.1 + 0.03 * (100 - color[0]) - 11.4 * (chroma ** 0.02)
    if hardness > max_hardness:
        hardness = max_hardness

    #normalize values
    activity = (activity - min_activity) / (max_activity - min_activity)
    heaviness = (heaviness - min_heaviness) / (max_heaviness - min_heaviness)
    warmth = (warmth - min_warmth) / (max_warmth - min_warmth)
    hardness = (hardness - min_hardness) / (max_hardness - min_hardness)
    
    #print "activity:", activity
    #print "warmth:", warmth
    #print "heaviness:", heaviness
    #print "hardness:", hardness
    
    return activity, heaviness, warmth, hardness

def removeSimilarColors(colors):
    #print "removeSimilarColors"
    distance_bw_colors_threshold= 20
    temp_colors = numpy.asarray(colors)
    #print temp_colors
    colors_to_remove = []
    for first_color in range(len(temp_colors)-1):
        for second_color in range(first_color+1,len(temp_colors)):
            dist = numpy.linalg.norm(temp_colors[first_color] - temp_colors[second_color])
            if dist < distance_bw_colors_threshold:
                colors_to_remove.append(second_color)
                #print "will delete:",temp_colors[second_color],"color indeces:",first_color,",",second_color
        temp_colors = numpy.delete(temp_colors, colors_to_remove, axis=0)
        colors_to_remove = []
    return temp_colors
#line = [[0,0],[5,0],[10,0]]
#determineCurvature(line)

def writeImageDataToDB(img_data):
    #print "writing to DB"
    con = mdb.connect(read_default_file='./.VivConnection.mysql');
    #print "con done"
    with con:
        cur = con.cursor()
        #print "executing"
        cur.execute("INSERT INTO image(image_data) VALUES(%s)", (img_data,))
        last_id = cur.lastrowid
        con.commit
        #print "finished DB insert:",last_id
    return last_id

def writeImageColorDataToDB(img_id, img):
    distinctColorString = ""
    for color in img.distinctColors:
        distinctColorString += "%d,%d,%d&" % (color[0],color[1],color[2])
        #print distinctColorString
        
    dominantColorString = ""
    for color in img.dom_colors:
        dominantColorString += "%d,%d,%d&" % (color[0],color[1],color[2])
        #print dominantColorString
        
    salientColorString = ""
    for color in img.most_sal_colors:
        salientColorString += "%d,%d,%d&" % (color[0],color[1],color[2])
        #print salientColorString
    
    con = mdb.connect(read_default_file='./.VivConnection.mysql');

    with con:
        cur = con.cursor()
        cur.execute("UPDATE image SET activity_score=%s,weight_score=%s,warmth_score=%s,hardness_score=%s WHERE image_id=%s",
                    (img.affectValues['activity'],img.affectValues['heaviness'],img.affectValues['warmth'],img.affectValues['hardness'],img_id))
        if len(img.distinctColors) > 0:
            cur.execute("UPDATE image SET distinct_colors=%s where image_id=%s",(distinctColorString,img_id))
        if len(img.dom_colors) > 0:
            cur.execute("UPDATE image SET dom_colors=%s where image_id=%s",(dominantColorString,img_id))
        if len(img.most_sal_colors) > 0:
            cur.execute("UPDATE image SET sal_colors=%s where image_id=%s",(salientColorString,img_id))
        con.commit
        #print "finished color data insert"
        
def getImageAffectFromDB(img_id):
    con = mdb.connect(read_default_file='./.VivConnection.mysql');
    with con:
        cur = con.cursor()
        cur.execute("SELECT activity_score,weight_score,warmth_score,hardness_score FROM image WHERE image_id=%s",(img_id,))
        for (activity_score, weight_score, warmth_score, hardness_score) in cur:
            return activity_score, weight_score, warmth_score, hardness_score
        
def getVaseFromDB(vase_id):
    con = mdb.connect(read_default_file='./.VivConnection.mysql');
    with con:
        cur = con.cursor()
        cur.execute("SELECT dna, num_base_pts, num_vertices, init_height, left_edge_x, right_edge_x, activity_score, weight_score, warmth_score, hardness_score, image_id FROM vase WHERE vase_id=%s",(vase_id,))
        for (dna, basePts, numVerts, height, leftX, rightX, activity, weight, warmth, hardness, image_id) in cur:
            fin = parseDNA(dna)
    return fin, basePts, numVerts, height, leftX, rightX, activity, weight, warmth, hardness, image_id
        
def parseDNA(dna):
    finalDNA = []
    result = dna.split("&")
    for op in result:
        if op != '':
            tempOp = []
            splitOp = op.split(",")
            for elem in splitOp:
                try:
                    value = int(elem)
                    tempOp.append(value)
                except ValueError:
                    tempOp.append(elem)
                    pass  # it was a string, not an int.
            finalDNA.append(tempOp)
    return finalDNA
