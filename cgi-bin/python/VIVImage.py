#!/usr/bin/env python
'''
Created on Jan 13, 2014

@author: Britton

This is a class which contains all image data, including expected emotional responses
'''
import numpy
import time
import cv2
from itertools import product
from collections import Counter
from collections import defaultdict
from collections import OrderedDict
import json
class VIVImage:

    def __init__(self):
        # rbg pixel data is a floating point number in the range (0,1)
        self.pixel_data = {}
        self.xyz_pixel_data = {}
        self.lab_pixel_data = {}
        self.avg = {}
        self.image = None
        self.dominant_colors = []
        self.affectValues = {}
        self.distinctColors = []
        self.most_sal_colors = []
        self.least_sal_colors = []
        self.dom_colors = []
        
    def lineDetection(self):
        temp_image = numpy.asarray(numpy.round(numpy.asarray(cv2.cvtColor(self.image, cv2.COLOR_LAB2BGR)) * 255, 0), numpy.uint8)
        color_conv = cv2.cvtColor(self.image, cv2.COLOR_BGR2GRAY)
        #cv2.imshow("color_conv",color_conv)
        swapped_image = numpy.asarray(numpy.round(numpy.asarray(color_conv), 0), numpy.uint8)
        #cv2.imshow("orig_swapped_image",swapped_image)
        
        #print "orig shape:",temp_image.swapaxes(1,0).shape
        edgeMap = cv2.Canny(temp_image.swapaxes(1,0), 50, 200)
        lines = cv2.HoughLines(edgeMap, 1, numpy.pi/180, 200)
        circles = cv2.HoughCircles(swapped_image,cv2.cv.CV_HOUGH_GRADIENT,1,100, param1=50,param2=30,minRadius=10,maxRadius=0)
        #return edgeMap
        
        #print "edgeMap dimension", edgeMap.shape
        three_channel_img = numpy.zeros((edgeMap.shape[0], edgeMap.shape[1], 3))
        for a in xrange(edgeMap.shape[0]):
            for b in xrange(edgeMap.shape[1]):
                #if edgeMap[a][b] != 0:
                    #swapped_image[a][b] = (0,255,0)
                
                three_channel_img[a][b] = swapped_image[b][a]
        
        #cv2.imshow("orig_3_channel",three_channel_img)
        
        #print "SIZE OF LINES",lines
        if lines != None:
            for rho,theta in lines[0]:
                a = numpy.cos(theta)
                b = numpy.sin(theta)
                x0 = a*rho
                y0 = b*rho
                x1 = int(x0 + 1000*(-b))
                y1 = int(y0 + 1000*(a))
                x2 = int(x0 - 1000*(-b))
                y2 = int(y0 - 1000*(a))
            
                cv2.line(three_channel_img,(x1,y1),(x2,y2),(255,0,0),2)

        #circles = numpy.uint16(numpy.around(circles))
        #print "SIZE OF CIRCLES", circles
        if circles != None:
            for i in circles[0,:]:
                # draw the outer circle
                cv2.circle(swapped_image,(i[0],i[1]),i[2],255,2)
                cv2.circle(three_channel_img,(i[0],i[1]),i[2],(0,255,0),2)
                # draw the center of the circle
                cv2.circle(swapped_image,(i[0],i[1]),2,255,3)
                
                #cv2.line(swapped_image,(0,0),(i[0],i[1]),(255,0,0),2)
        #cv2.imshow('swapped_image',swapped_image.swapaxes(1,0))
        return three_channel_img
        
        
    def createSaliencyMap(self):
        
        means = numpy.round(numpy.asarray([self.image[:,:,0].mean(), self.image[:,:,1].mean(), self.image[:,:,2].mean()]),0)

        #print "mean color:",means
        #print self.image.dtype

        temp_image = cv2.medianBlur(self.image, 5)

        #kernel = numpy.ones((5,5),numpy.float32)/25
        #temp_image = cv2.filter2D(self.image,-1,kernel)

        #temp_image = self.applyMedianFilter()
        '''
        cv2.imshow('image',numpy.asarray(numpy.round(numpy.asarray(cv2.cvtColor(temp_image, cv2.COLOR_LAB2BGR)) * 255, 0), numpy.uint8))
        cv2.waitKey(0)
        cv2.destroyAllWindows()
        '''
        #print temp_image
        
        dist = (temp_image - means) ** 2
        
        saliency_map = numpy.zeros((dist.shape[0], dist.shape[1], 3), numpy.float32)

        saliency_map[:,:,0] = numpy.sqrt(dist.sum(axis=2))
        #print "first try",saliency_map[10,10]
        #for l in xrange(dist.shape[0]):
        #    for c in xrange(dist.shape[1]):
        #        saliency_map[l][c][0] = numpy.sqrt(dist[l][c].sum())
        #        #print saliency_map[l][c]
        #print "second try",saliency_map[10,10]
            
               
        saliency_map = ((saliency_map - saliency_map.min()) / (saliency_map.max() - saliency_map.min())) * 100

        #print saliency_map
        return saliency_map
    
    def getSalientColors(self, saliency_map):
        sal_colors = []
        least_sal_colors = []
        most_dom_sal_colors = []
        least_sal_dom_colors = []
        
        raveled_image = self.image.reshape(self.image.shape[0] * self.image.shape[1], 3)
        saliency_map = saliency_map.reshape(saliency_map.shape[0] * saliency_map.shape[1], 3)
        saliency_map = saliency_map[:,0].ravel()
        
        mean_sal = saliency_map.mean()
        std_dev = saliency_map.std()
        
        #print "mean saliency:", mean_sal
        #print "std deviation:", std_dev
        #print "image shape:", raveled_image.shape
        #print "sal map shape:", saliency_map.shape
        
        '''
        This section is for the most salient colors
        '''
        threshold = mean_sal + (2.0 * std_dev)
        sal_colors = numpy.asarray([raveled_image[i] for i in xrange(saliency_map.shape[0]) if saliency_map[i] > threshold])
        if len(sal_colors) == 0:
            return None,None
                    
        l_dig_bins = numpy.linspace(0, 101, 8, True)
        a_dig_bins = numpy.linspace(-127, 121, 8, True)
        b_dig_bins = numpy.linspace(-127, 121, 8, True)
        
        digitized_L = numpy.digitize(sal_colors[:,0].ravel(), l_dig_bins)
        digitized_A = numpy.digitize(sal_colors[:,1].ravel(), a_dig_bins)
        digitized_B = numpy.digitize(sal_colors[:,2].ravel(), b_dig_bins)
        
        digitized_colors = zip(digitized_L, digitized_A, digitized_B)
        
        count_dict = defaultdict(int)
        salient_color_bins = []

        for bin_num in xrange(len(digitized_L)):
            count_dict[digitized_L[bin_num],digitized_A[bin_num],digitized_B[bin_num]] += 1
        ordered = sorted(count_dict.items(), key=lambda x: x[1],reverse=True)[:8]
        #print ordered
        
        for i in range(8):
            if i >= len(ordered):
                salient_color_bins.append(None)
            else:
                salient_color_bins.append(ordered[i][0])
                
                
        all_avgs = defaultdict(list)
        
        for i in xrange(len(digitized_L)):
            for k in range(len(ordered)):
                if digitized_colors[i] == salient_color_bins[k]:
                    all_avgs[k].append(sal_colors[i])
                    
        
        for i in range(len(ordered)):
            l_avg = int(round(numpy.asarray(all_avgs[i])[:,0].ravel().mean()))
            a_avg = int(round(numpy.asarray(all_avgs[i])[:,1].ravel().mean()))
            b_avg = int(round(numpy.asarray(all_avgs[i])[:,2].ravel().mean()))
            #print l_avg,a_avg,b_avg
            most_dom_sal_colors.append((l_avg, a_avg, b_avg))
            
        for i in range(len(ordered),8):
            most_dom_sal_colors.append(None)
            
            
        '''
        This section is for the least salient colors
        '''
        threshold = mean_sal - (1.0 * std_dev)
        least_sal_colors = numpy.asarray([raveled_image[i] for i in xrange(saliency_map.shape[0]) if saliency_map[i] < threshold])
        #print "least salient colors length: ", least_sal_colors.size
                    
        if least_sal_colors.size == 0:
            return most_dom_sal_colors, None            
        l_dig_bins = numpy.linspace(0, 101, 8, True)
        a_dig_bins = numpy.linspace(-127, 121, 8, True)
        b_dig_bins = numpy.linspace(-127, 121, 8, True)
        
        digitized_L = numpy.digitize(least_sal_colors[:,0].ravel(), l_dig_bins)
        digitized_A = numpy.digitize(least_sal_colors[:,1].ravel(), a_dig_bins)
        digitized_B = numpy.digitize(least_sal_colors[:,2].ravel(), b_dig_bins)
        
        digitized_colors = zip(digitized_L, digitized_A, digitized_B)
        
        count_dict = defaultdict(int)
        salient_color_bins = []

        for bin_num in xrange(len(digitized_L)):
            count_dict[digitized_L[bin_num],digitized_A[bin_num],digitized_B[bin_num]] += 1
        ordered = sorted(count_dict.items(), key=lambda x: x[1],reverse=True)[:8]
        #print ordered
        
        for i in range(8):
            if i >= len(ordered):
                salient_color_bins.append(None)
            else:
                salient_color_bins.append(ordered[i][0])
                
                
        all_avgs = defaultdict(list)
        
        for i in xrange(len(digitized_L)):
            for k in range(len(ordered)):
                if digitized_colors[i] == salient_color_bins[k]:
                    all_avgs[k].append(least_sal_colors[i])
                    
        
        for i in range(len(ordered)):
            l_avg = int(round(numpy.asarray(all_avgs[i])[:,0].ravel().mean()))
            a_avg = int(round(numpy.asarray(all_avgs[i])[:,1].ravel().mean()))
            b_avg = int(round(numpy.asarray(all_avgs[i])[:,2].ravel().mean()))
            #print l_avg,a_avg,b_avg
            least_sal_dom_colors.append((l_avg, a_avg, b_avg))
            
        for i in range(len(ordered),8):
            least_sal_dom_colors.append(None)
            
        
        return most_dom_sal_colors, least_sal_dom_colors
    
    
    '''
    def applyMedianFilter(self):
        final = self.image[:]
        print "final shape", final.shape
        members=[self.image[0,0]]*9
        for x in xrange(1,len(self.image)-1):
            for y in xrange(1,len(self.image[0])-1):
                members[0] = self.image[x-1,y-1]
                members[1] = self.image[x-1,y]
                members[2] = self.image[x-1,y+1]
                members[3] = self.image[x,y-1]
                members[4] = self.image[x,y]
                members[5] = self.image[x,y+1]
                members[6] = self.image[x+1,y-1]
                members[7] = self.image[x+1,y]
                members[8] = self.image[x+1,y+1]
                
                members = numpy.sort(members, 0)
                if x==10 and y==10:
                    print members
                final[x,y]=members[4]
                
        return final
    '''
   
   
    def findDominantColor(self):
        dominant_color_bins = []

        start = time.clock() 
        raveled_image = self.image.reshape(self.image.shape[0] * self.image.shape[1], 3)
        end = time.clock()
        elapsed = end - start
        #print "image reshape took: " , elapsed
        
        #max_l = numpy.max(raveled_image[:,0])
        #max_a = numpy.max(raveled_image[:,1])
        #max_b = numpy.max(raveled_image[:,2])
        
        #min_l = numpy.min(raveled_image[:,0])
        #min_a = numpy.min(raveled_image[:,1])
        #min_b = numpy.min(raveled_image[:,2])
        #print "$$$", max_l
        #print "$$$", min_l
        #print "$$$", max_a
        #print "$$$", min_a
        #print "$$$", max_b
        #print "$$$", min_b
        
        #L_bins = numpy.arange(0, 101, 10)
        l_dig_bins = numpy.linspace(0, 101, 8, True)
        #l_dig_bins = numpy.linspace(min_l-1, max_l+1, 5, True)
        #A_bins = numpy.arange(-127, 128, 8)
        a_dig_bins = numpy.linspace(-127, 121, 8, True)
        #a_dig_bins = numpy.linspace(min_a-1, max_a+1, 8, True)
        #B_bins = numpy.arange(-127, 128, 8)
        b_dig_bins = numpy.linspace(-127, 121, 8, True)
        #b_dig_bins = numpy.linspace(min_b-1, max_b+1, 8, True)
        
        #print l_dig_bins
        #print a_dig_bins
        #print b_dig_bins
        #print L_bins
        #print A_bins
        #print B_bins
        #print raveled_image[:,0].ravel()
        #start = time.clock()
        #histarray, [l_bins, a_bins, b_bins] = numpy.histogramdd(((self.image[:,:,0].ravel()),
        #(self.image[:,:,1].ravel()),
        #(self.image[:,:,2].ravel())), bins=(L_bins, A_bins, B_bins))
        #ix,iy,iz = numpy.where(histarray)
        #sorted_hist= []
        #for t in zip(ix, iy, iz, histarray[ix,iy,iz]):
        #    sorted_hist.append(t)
        #    print t
        #print sorted(sorted_hist, key=lambda x: x[-1], reverse=True)
        #end = time.clock()
        #elapsed = end - start
        #print "histogramming took: " , elapsed
        #print "#################################"    
        start = time.clock()
        digitized_L = numpy.digitize(raveled_image[:,0].ravel(), l_dig_bins)
        digitized_A = numpy.digitize(raveled_image[:,1].ravel(), a_dig_bins)
        digitized_B = numpy.digitize(raveled_image[:,2].ravel(), b_dig_bins)
        end = time.clock()
        elapsed = end - start
        #print "digitize only took: " , elapsed
        #print "#################################"
        digitized_colors = zip(digitized_L, digitized_A, digitized_B)
        end = time.clock()
        elapsed = end - start
        #print "digitize ravel took: " , elapsed
        #start = time.clock() 
        #raveled_image = zip(self.image[:,:,0].ravel(),self.image[:,:,1].ravel(),self.image[:,:,2].ravel())
        #end = time.clock()
        #elapsed = end - start
        #print "image ravel took: " , elapsed
        #print digitized_colors
        #print raveled_image

        #print "#################################"
        #count_l = Counter(digitized_colors)
        #print count_l
        start = time.clock()
        count_dict = defaultdict(int)

        for bin_num in xrange(len(digitized_L)):
            count_dict[digitized_L[bin_num],digitized_A[bin_num],digitized_B[bin_num]] += 1
        ordered = sorted(count_dict.items(), key=lambda x: x[1],reverse=True)[:8]
        #print ordered
        for i in range(8):
            if i >= len(ordered):
                dominant_color_bins.append(None)
            else:
                dominant_color_bins.append(ordered[i][0])
        #print "dom color=", dominant_color_bins
        end = time.clock()
        elapsed = end - start
        #print "finding dom color dictionary took: " , elapsed

        #ix, iy, iz = numpy.where(histarray)
        #dominant = 0
        #Now determine which is greatest
        #for t in zip(ix, iy, iz, histarray[ix,iy,iz]):
            #if t[3] > dominant:
                #dominant = t[3]
                #om_L = t[0]
                #dom_A = t[1]
                #dom_B = t[2]
        #print dom_L, dom_A, dom_B, histarray[dom_L,dom_A,dom_B]

        #l_sum = 0.0
        #l_count = 0
        #a_sum = 0.0
        #a_count = 0
        #b_sum = 0.0
        #b_count = 0
        #all_avgs = [(raveled_image[i],k) for i in xrange(len(digitized_colors)) for k in range(5) if digitized_colors[i] == dominant_color_bins[k]]
        #for pixel in dominant_pixels:
            #print raveled_image[pixel]
        #print dominant_pixels
        
        start = time.clock()
        all_avgs = defaultdict(list)
        #print "===>",digitized_L[0],digitized_A[0], digitized_B[0]
        for i in xrange(len(digitized_L)):
            color_bin = digitized_colors[i]
            for k in range(len(ordered)):
                if color_bin == dominant_color_bins[k]:
                    all_avgs[k].append(raveled_image[i])
        end = time.clock()
        elapsed = end - start
        #print "conglomerating binned data took: " , elapsed            
                    
        #all_avgs = numpy.asarray([raveled_image[i] for i in xrange(len(digitized_colors)) if digitized_colors[i] == dominant_color_bins[0]])
        #print len(all_avgs)
        
        start = time.clock()
        for i in range(len(ordered)):
            l_avg = int(round(numpy.asarray(all_avgs[i])[:,0].ravel().mean()))
            a_avg = int(round(numpy.asarray(all_avgs[i])[:,1].ravel().mean()))
            b_avg = int(round(numpy.asarray(all_avgs[i])[:,2].ravel().mean()))
            #print "**********"
            #print all_avgs
            #print l_avg,a_avg,b_avg
            self.dominant_colors.append((l_avg, a_avg, b_avg))
        end = time.clock()
        elapsed = end - start
        #print "averaging all data took: " , elapsed
            
        for i in range(len(ordered),8):
            self.dominant_colors.append(None)
        
        '''
        for i in xrange(len(digitized_colors)):
            if digitized_colors[i] == dominant_color_bins[0]:
                l_sum += raveled_image[i][0]
                l_count += 1
                a_sum += raveled_image[i][1]
                a_count += 1
                b_sum += raveled_image[i][2]
                b_count += 1
        print "dominant color1: ", l_sum/l_count, ",", a_sum/a_count, ",", b_sum/b_count
        for i in range(5):
            self.dominant_colors.append((l_sum/l_count, a_sum/a_count, b_sum/b_count))
        '''
        #end = time.clock()
        #elapsed = end - start
        #print "averaging dom color took: " , elapsed
        
        
        '''
        l_sum = 0.0
        l_count = 0
        a_sum = 0.0
        a_count = 0
        b_sum = 0.0
        b_count = 0
        
        l_values = self.image[:,:,0].ravel()
        a_values = self.image[:,:,1].ravel()
        b_values = self.image[:,:,2].ravel()

        for index in xrange(len(l_values)):
            if self.__isInBucket(self.l_bins, l_values[index], dom_L):
                if self.__isInBucket(self.a_bins, a_values[index], dom_A):
                    if self.__isInBucket(self.b_bins, b_values[index], dom_B):
                        l_sum += l_values[index]
                        l_count += 1
                        a_sum += a_values[index]
                        a_count += 1
                        b_sum += b_values[index]
                        b_count += 1
        print "dominant color: ", l_sum/l_count, ",", a_sum/a_count, ",", b_sum/b_count  
        '''
             
        #for color in (self.image[x,y] for y in range(self.image.shape[0]) for x in range(self.image.shape[1])):
        #    print color
        '''
        l_sum = 0.0
        l_count = 0
        a_sum = 0.0
        a_count = 0
        b_sum = 0.0
        b_count = 0
        [for x in xrange(self.image.shape[0])] for y in xrange(self.image.shape[1]):
            for y in xrange(self.image.shape[1]):
                cpixel = self.image[x,y]
                if self.__isInBucket(cpixel, [dom_L, dom_A, dom_B]):
                            l_sum += cpixel[0]
                            l_count += 1
                            a_sum += cpixel[1]
                            a_count += 1
                            b_sum += cpixel[2]
                            b_count += 1
        #print "dominant color: ", l_sum/l_count, ",", a_sum/a_count, ",", b_sum/b_count
        '''
        
          
        '''
                l_bucket = self.__determineLBucket(cpixel[0])
                a_bucket = self.__determineABucket(cpixel[1])
                b_bucket = self.__determineBBucket(cpixel[2])
    
                if (l_bucket, a_bucket, b_bucket) in self.hist.keys():
                    c_avg = self.hist.get((l_bucket, a_bucket, b_bucket))
                    self.hist[(l_bucket, a_bucket, b_bucket)] = ((self.__calcNewAverage(c_avg[0][0],c_avg[1],cpixel[0]), self.__calcNewAverage(c_avg[0][1],c_avg[1],cpixel[1]), self.__calcNewAverage(c_avg[0][2],c_avg[1],cpixel[2])), c_avg[1] + 1)
                else:
                    self.hist[(l_bucket, a_bucket, b_bucket)] = (cpixel, 1)
        print self.hist
        print ""
        '''
        return self.dominant_colors
    
    #pass in all buckets l_edge, a_edge, b_edge and the edges for the color you're looking or
    def __isInBucket(self, bins, value, edge):
        if edge < len(bins)-1 and bins[edge] < value <= bins[edge + 1]:
            return True
        '''
        if (edges[0] == len(self.l_bins) and self.l_bins[edges[0]] < pixel[0]) or self.l_bins[edges[0]] < pixel[0] < self.l_bins[edges[0] + 1]:
                    if (edges[1] == len(self.a_bins) and self.a_bins[edges[1]] < pixel[1]) or self.a_bins[edges[1]] < pixel[1] < self.a_bins[edges[1] + 1]:
                        if (edges[2] == len(self.b_bins) and self.b_bins[edges[2]] < pixel[2]) or self.b_bins[edges[2]] < pixel[2] < self.b_bins[edges[2] + 1]:
                            return True
        '''
        return False
        
        
    def setPixelData(self, image):
        pixels = image.load()
        self.width, self.height = image.size
        
        for y in range(self.height):
            for x in range(self.width):
                cpixel = pixels[x, y]
                new_pixel = self.__setRGBData(cpixel, x, y)

                new_pixel = self.__setXYZData(new_pixel, x, y)
                new_pixel = self.__setLABData(new_pixel, x, y)
                '''
                l_bucket = self.__determineLBucket(cpixel[0])
                a_bucket = self.__determineABucket(cpixel[1])
                b_bucket = self.__determineBBucket(cpixel[2])

                if (l_bucket, a_bucket, b_bucket) in self.hist.keys():
                    c_avg = self.hist.get((l_bucket, a_bucket, b_bucket))
                    self.hist[(l_bucket, a_bucket, b_bucket)] = ((self.__calcNewAverage(c_avg[0][0],c_avg[1],cpixel[0]), self.__calcNewAverage(c_avg[0][1],c_avg[1],cpixel[1]), self.__calcNewAverage(c_avg[0][2],c_avg[1],cpixel[2])), c_avg[1] + 1)
                else:
                    self.hist[(l_bucket, a_bucket, b_bucket)] = (cpixel, 1)
                '''
                               
    def __setRGBData(self, pixel, x, y):
        new_pixel = [pixel[0]/float(255), pixel[1]/float(255), pixel[2]/float(255)]
        self.pixel_data[(x,y)] = new_pixel
        
        if(x == 0 and y == 0):
            print str(pixel[0]) + "," + str(pixel[1]) + "," + str(pixel[2])
            print self.pixel_data[(x, y)]
            
        return new_pixel
        
    def __setXYZData(self, pixel, x, y):
        if(pixel[0] < 0.04045):
            r = pixel[0]/12.92
        else:
            r = pow((pixel[0] + 0.055)/1.055, 2.4)
        if(pixel[1] < 0.04045):
            g = pixel[1]/12.92
        else:
            g = pow((pixel[1] + 0.055)/1.055, 2.4)
        if(pixel[2] < 0.04045):
            b = pixel[2]/12.92
        else:
            b = pow((pixel[2] + 0.055)/1.055, 2.4)
        X =  0.412453*r     + 0.357580*g + 0.180423 *b
        Y =  0.212671*r     + 0.715160*g + 0.072169 *b
        Z =  0.019334*r     + 0.119193*g + 0.950227 *b
        
        new_pixel = [X, Y, Z]
        self.pixel_data[(x, y)] = new_pixel
        
        
        if(x == 0 and y == 0):
            print str(pixel[0]) + "," + str(pixel[1]) + "," + str(pixel[2])
            print self.pixel_data[(x, y)]
            
        return new_pixel
        
    def __setLABData(self, pixel, x, y):
        eps = pow((6/29.0), 3)
        a = (1/3.0) * pow((29/6.0), 2)
        # reference white D65
        Xr = 0.95047
        Yr = 1.0
        Zr = 1.08883
        
        X = pixel[0]
        Y = pixel[1]
        Z = pixel[2]
        
        xr = X/Xr
        yr = Y/Yr
        zr = Z/Zr

        if (xr > eps):
            fx =  pow(xr, 1/3.0)
        else:
            fx = a * xr + (4/29.0)

        if (yr > eps):
            fy =  pow(yr, 1/3.0)
        else:
            fy = a * yr + (4/29.0)

        if (zr > eps):
            fz =  pow(zr, 1/3.0)
        else:
            fz = a * zr + (4/29.0)

        L = (116 * fy) - 16
        A = 500*(fx-fy)
        B = 200*(fy-fz)
        
        new_pixel = [L, A, B]
        self.pixel_data[(x,y)] = new_pixel
        
        if(x == 0 and y == 0):
            print str(pixel[0]) + "," + str(pixel[1]) + "," + str(pixel[2])
            print self.pixel_data[(x, y)]
            
        return new_pixel
        
    def __determineLBucket(self, l_value):
        b0 = 0
        b1 = 10
        b2 = 20
        b3 = 30
        b4 = 40
        b5 = 50
        b6 = 60
        b7 = 70
        b8 = 80
        b9 = 90
        b10 = 100
        if l_value < b0:
            return -1
        elif l_value < b1:
            return 0
        elif l_value < b2:
            return 1
        elif l_value < b3:
            return 2
        elif l_value < b4:
            return 3
        elif l_value < b5:
            return 4
        elif l_value < b6:
            return 5
        elif l_value < b7:
            return 6
        elif l_value < b8:
            return 7
        elif l_value < b9:
            return 8
        elif l_value <= b10:
            return 9
        else:
            return -1
    
    def __determineABucket(self, a_value):
        b0 = -128
        b1 = -96
        b2 = -64
        b3 = -32
        b4 = 0
        b5 = 32
        b6 = 64
        b7 = 96
        b8 = 128
        if a_value < b0:
            return -1
        elif a_value < b1:
            return 0
        elif a_value < b2:
            return 1
        elif a_value < b3:
            return 2
        elif a_value < b4:
            return 3
        elif a_value < b5:
            return 4
        elif a_value < b6:
            return 5
        elif a_value < b7:
            return 6
        elif a_value <= b8:
            return 7
        else:
            return -1
        
    def __determineBBucket(self, b_value):
        b0 = -128
        b1 = -96
        b2 = -64
        b3 = -32
        b4 = 0
        b5 = 32
        b6 = 64
        b7 = 96
        b8 = 128
        if b_value < b0:
            return -1
        elif b_value < b1:
            return 0
        elif b_value < b2:
            return 1
        elif b_value < b3:
            return 2
        elif b_value < b4:
            return 3
        elif b_value < b5:
            return 4
        elif b_value < b6:
            return 5
        elif b_value < b7:
            return 6
        elif b_value <= b8:
            return 7
        else:
            return -1
        
    def __calcNewAverage(self, c_avg, num, new_add):
        return (c_avg * num + new_add) / float(num + 1)
