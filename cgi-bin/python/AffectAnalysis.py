#!/usr/bin/env python
'''
Created on May 13, 2014

@author: Britton Horn
'''
import numpy
import math
#import cv2
from collections import defaultdict
#from Vase import Vase
import Utilities
#import CreateVase

def analyzeAffectOfImage(image, lines, circles):
    #print "Analyzing image affect"
    distinct_colors = image.distinctColors
    #print "setting distinct colors"
    dom_colors = image.dom_colors
    most_sal_colors = image.most_sal_colors
    least_sal_colors = image.least_sal_colors
    #print "creating default dict"
    imageAffects = defaultdict(list)
    #print imageAffects
    #print "calculating color scores"
    activity, warmth, heaviness, hardness = Utilities.calcColorScores(distinct_colors, dom_colors, most_sal_colors, least_sal_colors)
    #print "finished calc color scores"
    return activity, warmth, heaviness, hardness
    #for index in range(len(distinct_colors)):
    #   imageAffects = distanceToColors(distinct_colors[index], imageAffects)
        
    #for i in range(len(imageAffects)):
    #    _sum = 0
    #    count = 0
    #    for i2 in range(len(imageAffects[i])):
    #        _sum = _sum + imageAffects[i][i2]
    #        count = count + 1
    #    imageAffects[i] = _sum/(count*1.0)
    
    #return imageAffects
    
def distanceToColors(color, imageAffects):
    #print "Getting distance to color: ",color
    tempColor = numpy.asarray(color)
    for i in range(6):
        dist = numpy.linalg.norm(tempColor - colorsToCompare[i])
        dist = dist * dist
        #print i, ":", dist
    
        C1 = math.sqrt(color[1]**2 + color[2]**2)
        C2 = math.sqrt(colorsToCompare[i][1]**2 + colorsToCompare[i][2]**2)
        deltaC = C1 - C2
        deltaL = color[0] - colorsToCompare[i][0]
        deltaA = color[1] - colorsToCompare[i][1]
        deltaB = color[2] - colorsToCompare[i][2]
        deltaH = deltaA**2 + deltaB**2 - deltaC**2
        S_C = ((.0638*C1)/(1+.0131*C1)) + .638
        H1 = math.atan2(color[2],color[1])
        H1 = H1 * (180/math.pi)
        if H1 < 0:
            H1 = H1 + 360
        if H1 > 360:
            H1 = H1 - 360
        F = math.sqrt((C1**4)/(C1**4 + 1900))
        
        if H1 >= 164 and H1 <= 345:
            T = .56 + abs(.2 * (math.cos(H1 + 168)))
        else:
            T = .36 + abs(.4 * (math.cos(H1 + 35)))
            
        S_H = S_C * (F*T + 1 - F)
        
        if color[0] < 16:
            S_L = .511
        else:
            S_L = (.040975 * color[0])/(1+(.01765*color[0]))
            
        deltaE = math.sqrt((deltaL/(2*S_L))**2 + (deltaC/S_C)**2 + (deltaH/(S_H**2)))
        deltaE_2 = deltaE**2
        #print i, "deltE ^ 2:", deltaE_2
        
        #distance squared of 100 means the colors are close enough
        deltaE_2 = deltaE_2 / 100.0
        if deltaE_2 <= 1.0:
            deltaE_2 = 1.0
        
        if deltaE_2 < 3.0:
            if i == _RED:
                for index in range(len(redAffect)):
                    affect = redAffect[index]
                    affectValue = redAffectValues[index] / deltaE_2
                    imageAffects[affect].append(affectValue)
            elif i == _GREEN:
                for index in range(len(greenAffect)):
                    affect = greenAffect[index]
                    affectValue = greenAffectValues[index] / deltaE_2
                    imageAffects[affect].append(affectValue)
            elif i == _BLUE:
                for index in range(len(blueAffect)):
                    affect = blueAffect[index]
                    affectValue = blueAffectValues[index] / deltaE_2
                    imageAffects[affect].append(affectValue)
            elif i == _YELLOW:
                for index in range(len(yellowAffect)):
                    affect = yellowAffect[index]
                    affectValue = yellowAffectValues[index] / deltaE_2
                    imageAffects[affect].append(affectValue)
            elif i == _WHITE:
                for index in range(len(whiteAffect)):
                    affect = whiteAffect[index]
                    affectValue = whiteAffectValues[index] / deltaE_2
                    imageAffects[affect].append(affectValue)
            elif i == _BLACK:
                for index in range(len(blackAffect)):
                    affect = blackAffect[index]
                    affectValue = blackAffectValues[index] / deltaE_2
                    imageAffects[affect].append(affectValue)
            else:
                "INVALID COLOR"
        
    #return imageAffects
     
    
def analyzeAffectOfVase(vase):
    #print "analyzing affect of vase"
    #left = vase.finalLeft
    #right = vase.finalRight
    
    #leftLinearity = Utilities.determineLinearity(vase.finalLeft)
    #rightLinearity = Utilities.determineLinearity(vase.finalRight)
    
    #linearityAvg = (rightLinearity+leftLinearity)/2.0
    
    left_height = vase.leftedge[-1][1]
    right_height = vase.rightedge[-1][1]
    num_pts_base = vase.basePts
    min_width = vase.minWidth
    added_heights = left_height + right_height
    added_heights = float(added_heights)
    if min_width < 15 or left_height < 15 or right_height < 15 or left_height/added_heights < .4 or right_height/added_heights < .4:
        vase.affectValues['warmth'] = -1.0
        vase.affectValues['heaviness'] = -1.0
        vase.affectValues['activity'] = -1.0
        vase.affectValues['hardness'] = -1.0
        return
    max_width = vase.maxWidth
    left_inf_pts_ratio = len(vase.leftVerticalPoints) / float(len(vase.leftedge))
    right_inf_pts_ratio = len(vase.rightVerticalPoints) / float(len(vase.rightedge))
    total_inf_pts_ratio = (len(vase.leftVerticalPoints) + len(vase.rightVerticalPoints)) / float(len(vase.leftedge) + len(vase.rightedge))
    base_to_lip = vase.baseToLipRatio
    min_width_height = vase.minWidthToHeight
    max_width_height = vase.maxWidthToHeight
    center_mass_x = vase.centerOfMassX
    cm_x_ratio = abs(vase.centerOfMassX / vase.baseWidth)
    if cm_x_ratio < 0.00001:
        cm_x_ratio = 0
    center_mass_y = vase.centerOfMassY
    cm_y_ratio = abs(vase.centerOfMassY / vase.height)
    
    left_min_height_ratio = vase.leftedge[vase.leftMinWidthIndex][1] / vase.height
    left_max_height_ratio = vase.leftedge[vase.leftMaxWidthIndex][1] / vase.height
    right_min_height_ratio = vase.rightedge[vase.rightMinWidthIndex][1] / vase.height
    right_max_height_ratio = vase.rightedge[vase.rightMaxWidthIndex][1] / vase.height
    
    '''
    #Attempt 1
    #Warmth
    if (num_pts_base < 7):
        warmth_score = (
            -0.0852 * max_width_height 
            - 1.0817 * cm_x_ratio 
            + 0.0011 * num_pts_base 
            + 0.5083)
    else:
        warmth_score = (
            -3.8301 * cm_x_ratio 
            + 0.0008 * num_pts_base 
            + 0.5637)
            
    #Weight
    #weight_score = abs(cm_y_ratio - 1)
    
    if min_width_height <= 0.206 : 
        if right_max_height_ratio <= 0.324 :
                weight_score = (
                    0.5666 * min_width_height 
                    - 0.7129 * cm_y_ratio 
                    - 0.1109 * right_max_height_ratio 
                    + 0.8022)
        else:
            weight_score = (
                0.4568 * min_width_height 
                - 0.7129 * cm_y_ratio 
                - 0.0355 * right_max_height_ratio 
                + 0.7182)
    else:
        weight_score = (
            0.0366 * min_width_height 
            - 0.287 * cm_y_ratio 
            - 0.2061 * right_max_height_ratio 
            + 0.7702)
    
    #Activity
    activity_score = (
        -0.2657 * total_inf_pts_ratio 
        + 6.1139 * cm_x_ratio 
        + 0.6219)
        
    #Hardness
    if num_pts_base < 7 :
        hardness_score = (
            0.0457 * left_inf_pts_ratio 
            - 0.0572 * total_inf_pts_ratio 
            + 0.0586 * min_width_height 
            + 0.106 * max_width_height 
            - 0.0453 * left_min_height_ratio 
            - 0.0016 * num_pts_base 
            + 0.598)
    else:
        hardness_score = (
            0.0339 * left_inf_pts_ratio 
            - 0.0424 * total_inf_pts_ratio 
            + 0.132 * min_width_height 
            - 0.0336 * left_min_height_ratio 
            - 0.0012 * num_pts_base 
            + 0.4656)
    '''
    
    #Attempt 2
    if (num_pts_base < 7):
        warmth_score = (
            -1.9623 * cm_x_ratio 
            + 0.0014 * num_pts_base 
            + 0.4086)
    else:
        warmth_score = (
            -7.7225 * cm_x_ratio 
            + 0.001 * num_pts_base 
            + 0.5824)
            
    #Weight
    #weight_score = abs(cm_y_ratio - 1)
    
    if min_width_height <= 0.206 : 
        if right_max_height_ratio <= 0.324 :
                weight_score = (
                    0.6154  * min_width_height 
                    - 0.3178 * right_max_height_ratio 
                    + 0.535)
        else:
            weight_score = (
                0.5102 * min_width_height 
                - 0.2778 * right_max_height_ratio 
                + 0.4595)
    else:
        weight_score = (
            0.0642 * min_width_height 
            - 0.0582 * right_max_height_ratio 
            + 0.5906)
    
    #Activity
    if cm_x_ratio <= 0.009 : 
        if right_inf_pts_ratio <= 0.317 :
                
            activity_score = (
                -0.2734 * right_inf_pts_ratio 
                + 2.27 * cm_x_ratio 
                - 0.0065 * num_pts_base 
                + 0.7246)
        else:
            activity_score = (
                -0.1716 * right_inf_pts_ratio 
                + 2.27 * cm_x_ratio 
                - 0.0019 * num_pts_base 
                + 0.4929)
    else:
        activity_score = (
            -0.12 * right_inf_pts_ratio 
            + 3.7095 * cm_x_ratio 
            - 0.0015 * num_pts_base 
            + 0.6847)  
        
    #Hardness
    hardness_score = (
        0.174 * max_width_height 
        - 1.7853 * cm_y_ratio 
        - 0.217 * left_min_height_ratio 
        - 0.0094 * num_pts_base 
        + 1.5696)
        
        
    vase.affectValues['warmth'] = warmth_score
    vase.affectValues['heaviness'] = weight_score
    vase.affectValues['activity'] = activity_score
    vase.affectValues['hardness'] = hardness_score
    '''
    #warmth factors
    vase.affectValues['warmth'].append(asymmetry)
    vase.affectValues['warmth'].append(nonlinearity)
    if base_to_lip < 1.0:
        vase.affectValues['warmth'].append((base_to_lip - 1) * -1)
    if center_mass_y > height/2.0:
        norm_y = (height - center_mass_y) / (height/2.0)
        warmth_contrib = (norm_y - 1) * -1
        vase.affectValues['warmth'].append(warmth_contrib)
        
    
    #weight factors
    if center_mass_y < height/2.0:
        norm_y = center_mass_y / (height/2.0)
        heaviness_contrib = (norm_y - 1) * -1
        vase.affectValues['heaviness'].append(heaviness_contrib)
    else:
        vase.affectValues['heaviness'].append(0.0)
    
    #activity factors
    inf_con = 0
    if len(vase.rightInfPts) > 6:
        inf_con += .5
    if len(vase.leftInfPts) > 6:
        inf_con += .5
    vase.affectValues['activity'].append(inf_con)
    vase.affectValues['activity'].append(asymmetry)
    if center_mass_y > height/2.0:
        norm_y = (height - center_mass_y) / (height/2.0)
        activity_contrib = (norm_y - 1) * -1
        vase.affectValues['activity'].append(activity_contrib)
    if base_to_lip < 1.01 and base_to_lip > .99:
        vase.affectValues['activity'].append(1.0)
    
    #hardness factors
    vase.affectValues['hardness'].append(linearityAvg)
    width_ratio = vase.maxWidth / vase.minWidth
    if width_ratio < 1.1 and width_ratio > .9:
        vase.affectValues['hardness'].append(1.0)
    if vase.maxWidthToHeight < 1.0:
        vase.affectValues['hardness'].append((vase.maxWidthToHeight - 1) * -1)
    if center_mass_y < height/2.0:
        norm_y = center_mass_y / (height/2.0)
        hardness_contrib = (norm_y - 1) * -1
        vase.affectValues['hardness'].append(hardness_contrib)
    if symmetry > .95:
        vase.affectValues['hardness'].append(1.0)
    
    #print "vase affect values:",vase.affectValues

    for key in vase.affectValues:
        _sum = 0
        _count = 0
        for i2 in range(len(vase.affectValues[key])):
            _sum = _sum + vase.affectValues[key][i2]
            _count = _count + 1
        vase.affectValues[key] = _sum/(_count*1.0)
    '''
    #CreateVase.draw(left, right)
    #print "vase affect values:",vase.affectValues
    #print leftLinearity
    #print rightLinearity
    
    '''
    #print left
    #print right
    
    img = numpy.zeros((160,160,3), numpy.uint8)
    pts = []
    for i in range(len(right)):
        pts.append((int(right[i][0]+50),int(160-right[i][1])))
    
    for i in reversed(range(len(left))):
        pts.append((int(left[i][0]+50),int(160-left[i][1])))
        
    pts = numpy.asarray(pts, numpy.int32)
    #print pts
    cv2.fillPoly(img, [pts], (255,255,255))
    img = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    edgeMap = cv2.Canny(img, 5, 20)
    lines = cv2.HoughLines(edgeMap, 1, numpy.pi/180, 50)
    circles = cv2.HoughCircles(img,cv2.cv.CV_HOUGH_GRADIENT,1,50, param1=20,param2=5,minRadius=10,maxRadius=0)
    #print lines
    #print circles
    cv2.imshow('rnd vase', img)
    cv2.waitKey(0)
    cv2.destroyAllWindows()
    '''
    
_RED = 0
_GREEN = 1
_BLUE = 2
_YELLOW = 3
_WHITE = 4
_BLACK = 5

red = [52.0,71.0,42.0] #237,41,57
green = [64.0,-57.0,46.0] #50,177,65
blue = [33.0,6.0,-38.0] #29,78,137
yellow = [89.0,-6.0,88.0] #254,223,0
white = [95.0,0.0,0.0] #241,242,241
black = [12.0,1.0,3.0] #42,38,35

colorsToCompare = [red, green, blue, yellow, white, black]

affects = ['energetic', 'aggressive', 'passion', 'cold', 'loyal', 'calm', 
           'balance', 'soothing', 'scientific', 'harmony', 'peace', 'organic', 
           'sleek', 'sharp', 'danger', 'purity', 'sterile', 'simple', 'friendly', 'optimistic', 'confident']
_ENERGETIC = 0
_AGGRESSIVE = 1
_PASSION = 2
_COLD = 3
_LOYAL = 4
_CALM = 5
_BALANCE = 6
_SOOTHING = 7
_SCIENTIFIC = 8
_HARMONY = 9
_PEACE = 10
_ORGANIC = 11
_SLEEK = 12
_SHARP = 13
_DANGER = 14
_PURITY = 15
_STERILE = 16
_SIMPLE = 17
_FRIENDLY = 18
_OPTIMISTIC = 19
_CONFIDENT = 20

# image color metics
redAffect = [_ENERGETIC, _AGGRESSIVE, _PASSION, _DANGER, _CONFIDENT, _OPTIMISTIC, _HARMONY, _SHARP, _SCIENTIFIC, _LOYAL, _PURITY, _SLEEK, _BALANCE, _SIMPLE, _ORGANIC, _PEACE, _FRIENDLY, _SOOTHING, _STERILE, _COLD, _CALM]
redAffectValues = [1.0, 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, -1.0, -1.0, -1.0]
greenAffect = [_ORGANIC, _CALM, _PEACE, _BALANCE, _HARMONY, _SOOTHING, _OPTIMISTIC, _PURITY, _FRIENDLY, _CONFIDENT, _LOYAL, _SIMPLE, _SLEEK, _SHARP, _STERILE, _SCIENTIFIC, _COLD, _DANGER, _ENERGETIC, _AGGRESSIVE, _PASSION]
greenAffectValues = [1.0, 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, -1.0, -1.0, -1.0]
blueAffect = [_COLD, _LOYAL, _CALM, _SOOTHING, _SCIENTIFIC, _PEACE, _ORGANIC, _PURITY, _SLEEK, _BALANCE, _SIMPLE, _SHARP, _STERILE, _FRIENDLY, _OPTIMISTIC, _HARMONY, _CONFIDENT, _DANGER, _ENERGETIC, _AGGRESSIVE, _PASSION]
blueAffectValues = [1.0, 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, -1.0, -1.0, -1.0]
yellowAffect = [_LOYAL, _OPTIMISTIC, _FRIENDLY, _CONFIDENT, _ENERGETIC, _AGGRESSIVE, _SOOTHING, _ORGANIC, _PEACE, _SHARP, _BALANCE, _HARMONY, _SLEEK, _PASSION, _STERILE, _DANGER, _SIMPLE, _PURITY, _SCIENTIFIC, _CALM, _COLD]
yellowAffectValues = [1.0, 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, -1.0, -1.0, -1.0]
whiteAffect = [_PURITY, _STERILE, _SIMPLE, _CALM, _COLD, _PEACE, _HARMONY, _LOYAL, _CONFIDENT, _SCIENTIFIC, _SLEEK, _SOOTHING, _SHARP, _BALANCE, _OPTIMISTIC, _FRIENDLY, _ORGANIC, _DANGER, _ENERGETIC, _AGGRESSIVE, _PASSION]
whiteAffectValues = [1.0, 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, -1.0, -1.0, -1.0]
blackAffect = [_DANGER, _SLEEK, _SHARP, _AGGRESSIVE, _COLD, _SIMPLE, _BALANCE, _SCIENTIFIC, _CONFIDENT, _HARMONY, _PEACE, _LOYAL, _PURITY, _STERILE, _OPTIMISTIC, _ORGANIC, _ENERGETIC, _CALM, _FRIENDLY, _SOOTHING, _PASSION]
blackAffectValues = [1.0, 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, -1.0, -1.0, -1.0]

# Vase metrics
symmAffect = [_BALANCE, _CALM, _HARMONY, _SOOTHING, _PURITY, _ORGANIC, _PEACE, _CONFIDENT, _OPTIMISTIC, _FRIENDLY, _SIMPLE, _LOYAL, _SLEEK, _PASSION, _ENERGETIC, _SHARP, _SCIENTIFIC, _COLD, _STERILE, _AGGRESSIVE, _DANGER]
symmAffectValues = [1.0, 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, -1.0, -1.0, -1.0]
height_to_width_affect = []
height_to_width_affect_values = []
linearAffect = [_SHARP, _DANGER, _AGGRESSIVE, _COLD, _SLEEK, _CONFIDENT, _SCIENTIFIC, _STERILE, _SIMPLE, _BALANCE, _ENERGETIC, _PASSION, _LOYAL, _HARMONY, _PURITY, _PEACE, _OPTIMISTIC, _CALM, _FRIENDLY, _SOOTHING, _ORGANIC]
linearAffectValues = [1.0, 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, -1.0, -1.0, -1.0]
lip_to_base_affect = []
lip_to_base_affect_values = []
sa_to_volume_affect = []
sa_to_volume_affect_values = []
