#!/usr/bin/env python
'''
Created on Apr 24, 2014

@author: Britton
'''
import random
import numpy as np
from collections import defaultdict
import time
import Utilities as util

class Vase():

    def __init__(self):
        self.numVertices = 20
        self.leftedgeX = random.randint(-30, -15)
        self.rightedgeX = self.leftedgeX * -1
        randomHeight = random.randint(3, 8)
        self.leftedge = np.zeros((self.numVertices,2), np.float32)
        #print self.leftedge.shape
        self.rightedge = np.zeros((self.numVertices,2), np.float32)
        for i in range(self.numVertices):
            self.leftedge[i] = [self.leftedgeX, i*randomHeight]
            self.rightedge[i] = [self.rightedgeX, i*randomHeight]
            
        self.affectValues = defaultdict(list)
            
        self.finalRight = []
        self.finalLeft = []
        self.DNA = []
        self.parentDNA = []
        self.initHeight = randomHeight
        self.basePts = random.randint(3,9)
        if self.basePts == 9:
            self.basePts = 32
        #print "original left", self.leftedge
        #print "original right", self.rightedge
        
    def shorten(self, scale, side):
        leftHeight = self.leftedge[self.numVertices - 1][1] - self.leftedge[0][1]
        #print "left height:",leftHeight
        rightHeight = self.rightedge[self.numVertices - 1][1] - self.rightedge[0][1]
        leftDiff = (1-scale) * leftHeight
        rightDiff = (1-scale) * rightHeight
        
        for i in range(self.numVertices):
            if side == "RIGHT" or side == "BOTH":
                self.rightedge[i][1] *= scale
                #self.rightedge[i][1] += rightDiff
            if side == "LEFT" or side == "BOTH":
                self.leftedge[i][1] *= scale
                #self.leftedge[i][1] += leftDiff
        self.DNA.append(['shorten',scale,side])
                
    def pull(self, depth, height, side):
        self.squeeze(-1*depth, height, side)
        
    def squeeze(self, depth, height, side):
        startIndex = random.randint(0, self.numVertices - height)
        offsets = [0 for i in range(height)]
        offsets[height/2] = depth
        for i in range(height/2):
            offsets[i] = i*depth/(height/2)
        counter = 0
        for j in reversed(range(height/2, height-1)):
            offsets[j] = counter*depth/(height/2)
            counter+=1
        
        counter = 0
        if side == "LEFT" or side == "BOTH":
            for v in range(startIndex, startIndex + height):
                self.leftedge[v][0] += offsets[counter]
                if self.leftedge[v][0] > -6:
                    self.leftedge[v][0] = -6
                counter+=1
        
        counter = 0
        if side == "RIGHT" or side == "BOTH":
            for v in range(startIndex, startIndex + height):
                self.rightedge[v][0] -= offsets[counter]
                if self.rightedge[v][0] < 6:
                    self.rightedge[v][0] = 6
                counter+=1
                
        #print "squeezed left",self.leftedge
        #print "squeezed right", self.rightedge
        self.DNA.append(['squeeze',depth,height,side])
        
    def calculateVaseMetrics(self):
        #print "calculating vase metrics"
        maxWidth = None
        minWidth = None
        rightVerticalPoints = []
        leftVerticalPoints = []
        #start = time.clock()
        right_y_values = [coor[1] for coor in self.rightedge]
        for left in range(len(self.leftedge)):
            rightIndex = util.findClosestInArray(right_y_values, self.rightedge[left][1])
            if left != len(self.leftedge)-1:
                if self.leftedge[left+1][0] - self.leftedge[left][0] < 0.01 and self.leftedge[left+1][0] - self.leftedge[left][0] > -0.01:
                        leftVerticalPoints.append(left)
                if self.rightedge[left+1][0] - self.rightedge[left][0] < 0.01 and self.rightedge[left+1][0] - self.rightedge[left][0] > -0.01:
                        rightVerticalPoints.append(left)
            diff = self.rightedge[rightIndex][0] - self.leftedge[left][0]
            if maxWidth == None or diff > maxWidth:
                maxWidth = diff
                self.leftMaxWidthIndex = left
                self.rightMaxWidthIndex = rightIndex
            if minWidth == None or diff < minWidth:
                minWidth = diff
                self.leftMinWidthIndex = left
                self.rightMinWidthIndex = rightIndex

            '''
            if left != len(self.finalLeft)-1:
                if self.finalLeft[left+1][0] - self.finalLeft[left][0] < 0.01 and self.finalLeft[left+1][0] - self.finalLeft[left][0] > -0.01:
                    leftVerticalPoints.append(left)
            for right in range(len(self.finalRight)):
                if left == right and right != len(self.finalRight)-1:
                    if self.finalRight[right+1][0] - self.finalRight[right][0] < 0.01 and self.finalRight[right+1][0] - self.finalRight[right][0] > -0.01:
                        rightVerticalPoints.append(right)
                if abs(self.finalRight[right][1] - self.finalLeft[left][1]) <= 0.11:
                    diff = self.finalRight[right][0] - self.finalLeft[left][0]
                    if maxWidth == None or diff > maxWidth:
                        maxWidth = diff
                        self.leftMaxWidthIndex = left
                        self.rightMaxWidthIndex = right
                    if minWidth == None or diff < minWidth:
                        minWidth = diff
                        self.leftMinWidthIndex = left
                        self.rightMinWidthIndex = right
            '''
        '''
        potential_indices = []
        right_shorten = 1
        left_shorten = 1
        for change in self.DNA:
            if change[0] == 'squeeze':
                potential_indices.append(change[2]/2)
            else:
                if change[2] == 'BOTH':
                    right_shorten *= change[1]
                    left_shorten *= change[1]
                if change[2] == 'RIGHT':
                    right_shorten *= change[1]
                if change[2] == 'LEFT':
                    left_shorten *= change[1]
        if len(potential_indices) == 0:
            diff = self.rightedge[0][0] - self.leftedge[0][0]
            self.maxWidth = diff
            self.minWidth = diff
        else:
        '''
        #end = time.clock()
        #elapsed = end - start   
        #print "finding max/min widths:",elapsed     
                   
        self.leftVerticalPoints = leftVerticalPoints
        self.rightVerticalPoints = rightVerticalPoints
        self.minWidth = minWidth
        self.maxWidth = maxWidth
        self.baseWidth = self.rightedge[0][0] - self.leftedge[0][0]
        self.lipWidth = self.rightedge[-1][0] - self.leftedge[-1][0]
        self.baseToLipRatio = self.baseWidth / float(self.lipWidth)
        if self.leftedge[-1][1] > self.rightedge[-1][1]:
            self.height = float(self.leftedge[-1][1])
        else:
            self.height = float(self.rightedge[-1][1])

        self.minWidthToHeight = self.minWidth / self.height
        self.maxWidthToHeight = self.maxWidth / self.height
            
        self.centerOfMassX, self.centerOfMassY = self._centerOfMass()
        self.leftInfPts, self.rightInfPts = self._pointsOfInflection()
        
        #print "left vertical pts:", self.leftVerticalPoints
        #print "right vertical pts:", self.rightVerticalPoints
        #print "height:", self.height
        #print "min width:", self.minWidth
        #print "min width left index:", self.leftMinWidthIndex
        #print "min width right index:", self.rightMinWidthIndex
        #print "max width:", self.maxWidth
        #print "max width left index:", self.leftMaxWidthIndex
        #print "max width right index:", self.rightMaxWidthIndex
        #print "base to Lip ratio:", self.baseToLipRatio
        #print "min width to height ratio:", self.minWidthToHeight
        #print "max width to height ratio:", self.maxWidthToHeight
        #print "center of mass:", self.centerOfMassX, self.centerOfMassY
        
    def _centerOfMass(self):
        xsum = 0
        ysum = 0
        area = 0
        
        for left in reversed(range(len(self.leftedge))):
            x0 = self.leftedge[left][0]
            y0 = self.leftedge[left][1]
            
            if left != 0:
                x1 = self.leftedge[left-1][0]
                y1 = self.leftedge[left-1][1]
            else:
                x1 = self.rightedge[0][0]
                y1 = self.rightedge[0][1]
                
            areaSum = (x0 * y1) - (x1 * y0)
            area = area + areaSum
            
            xsum = xsum + ((x0 + x1) * areaSum)
            ysum = ysum + ((y0 + y1) * areaSum)
        for right in range(len(self.rightedge)):
            x0 = self.rightedge[right][0]
            y0 = self.rightedge[right][1]
            
            if right != len(self.rightedge)-1:
                x1 = self.rightedge[right+1][0]
                y1 = self.rightedge[right+1][1]
            else:
                x1 = self.leftedge[-1][0]
                y1 = self.leftedge[-1][1]
                
            areaSum = (x0 * y1) - (x1 * y0)
            area = area + areaSum
            
            xsum = xsum + ((x0 + x1) * areaSum)
            ysum = ysum + ((y0 + y1) * areaSum)
            
        area = abs(area) / 2.0
        center_x = xsum / (6 * area)
        center_y = ysum / (6 * area)
        
        return center_x, center_y
    
    def _pointsOfInflection(self):
        direction = None
        epsilon = 0.01
        
        left_inflection_pts = []
        right_inflection_pts = []
        
        for i in range(len(self.leftedge)-1):
            if self.leftedge[i][0] - self.leftedge[i+1][0] > 0 + epsilon and direction != "right":
                direction = "right"
                left_inflection_pts.append(i)
            if self.leftedge[i][0] - self.leftedge[i+1][0] < 0 - epsilon and direction != "left":
                direction = "left"
                left_inflection_pts.append(i)
                
        direction = None
                
        for i in range(len(self.rightedge)-1):
            if self.rightedge[i][0] - self.rightedge[i+1][0] > 0 + epsilon and direction != "right":
                direction = "right"
                right_inflection_pts.append(i)
            if self.rightedge[i][0] - self.rightedge[i+1][0] < 0 - epsilon and direction != "left":
                direction = "left"
                right_inflection_pts.append(i)
                
        #print "left pts of Inflection:", left_inflection_pts
        #print "right pts of Inflection:", right_inflection_pts
                
        return left_inflection_pts, right_inflection_pts
        
