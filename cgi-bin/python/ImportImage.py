#!/usr/bin/env python
'''
Created on Jan 10, 2014

@author: Britton Horn
'''
from PIL import Image
from VIVImage import VIVImage
import numpy
import cv2
import time
import Utilities
import cStringIO

def importImageFromData(imgdata):
    #print "importImageData"
    b64 = imgdata.decode('base64')
    img = Image.open(cStringIO.StringIO(b64)).convert('RGB')
    #writeImageDataToDB(imgdata)
    img_id = Utilities.writeImageDataToDB(imgdata)
    return importImage(img, img_id), img_id
    
def importImageFromFile(filename, chosen_pic):
    #print "importImageFromFile:", filename
    img = Image.open(filename).convert('RGB')
    #writeImageDataToDB(imgdata)
    img_id = int(chosen_pic)
    return importImage(img, img_id), img_id 
    
def importImage(img, img_id):
    '''
    print "importImage:\n",fileName
    fileName = fileName.decode('base64')
    print "decoded"
    img = Image.open(cStringIO.StringIO(fileName)).convert('RGB')
    print "image opened"
    '''
    size = (500,500)
    img.thumbnail(size, Image.ANTIALIAS)

    #cv2.imshow('orig_image',cv2.cvtColor(numpy.asarray(img), cv2.COLOR_BGR2RGB))
    #cv2.imwrite('Bride.jpg', cv2.cvtColor(numpy.asarray(img), cv2.COLOR_BGR2RGB))
    #cv2.waitKey(0)
    #cv2.destroyAllWindows()
    arr = numpy.asarray(img, numpy.float32)
    arr = numpy.divide(arr, 255.0)
    
    #arr = cv2.resize(arr, (0,0), fx=0.5, fy=0.5)
    
     
    arr = cv2.cvtColor(arr, cv2.COLOR_RGB2LAB)
    #print arr.shape
    vivImage = VIVImage()
    #print arr.shape
    vivImage.image = numpy.asarray(numpy.round(arr.swapaxes(1,0),0), numpy.float32)
    #print "orig shape:",vivImage.image.shape
    #print vivImage.image
    #print vivImage.image[0,0]
    #print vivImage.image[10, 0]
    #print vivImage.image[0, 10]
    
    start = time.clock()
    vivImage.findDominantColor()
    end = time.clock()
    elapsed = end - start
    #print "finding dom color took: " , elapsed
    #print "#######################"
    #print vivImage.dominant_colors[0]
    #print vivImage.dominant_colors[1]
    #print vivImage.dominant_colors[2]
    #print vivImage.dominant_colors[3]
    #print vivImage.dominant_colors[4]
    size_factor = 2
    blank_image = numpy.zeros((200*size_factor,200*size_factor,3), numpy.float32)
    blank_image[:,0:25*size_factor] = vivImage.dominant_colors[0] if vivImage.dominant_colors[0] != None else [100,0,0]
    blank_image[:,25*size_factor:50*size_factor] = vivImage.dominant_colors[1] if vivImage.dominant_colors[1] != None else [100,0,0]
    blank_image[:,50*size_factor:75*size_factor] = vivImage.dominant_colors[2] if vivImage.dominant_colors[2] != None else [100,0,0]
    blank_image[:,75*size_factor:100*size_factor] = vivImage.dominant_colors[3] if vivImage.dominant_colors[3] != None else [100,0,0]
    blank_image[:,100*size_factor:125*size_factor] = vivImage.dominant_colors[4] if vivImage.dominant_colors[4] != None else [100,0,0]
    blank_image[:,125*size_factor:150*size_factor] = vivImage.dominant_colors[5] if vivImage.dominant_colors[5] != None else [100,0,0]
    blank_image[:,150*size_factor:175*size_factor] = vivImage.dominant_colors[6] if vivImage.dominant_colors[6] != None else [100,0,0]
    blank_image[:,175*size_factor:200*size_factor] = vivImage.dominant_colors[7] if vivImage.dominant_colors[7] != None else [100,0,0]
    #print blank_image.shape
    conv_image = numpy.asarray(numpy.round(numpy.asarray(cv2.cvtColor(blank_image, cv2.COLOR_LAB2BGR)) * 255, 0), numpy.uint8)
    #print "#######################"
    #print conv_image
    #print conv_image[0,0]
    
    #
    #cv2.imwrite('image.jpg', conv_image)
    #conv_image = cv2.imread('image.jpg')
    #
    #cv2.imshow('dom_colors',conv_image)
    #cv2.waitKey(0)
    #cv2.destroyAllWindows()
    #print vivImage.dominant_colors
    
    start = time.clock()
    sal_map = vivImage.createSaliencyMap()
    end = time.clock()
    elapsed = end - start
    #print "creating saliency map took: " , elapsed
    #find salient colors from saliency map
    start = time.clock()
    most_salient_colors, least_salient_colors = vivImage.getSalientColors(sal_map)
    #print most_salient_colors
    #print least_salient_colors
    end = time.clock()
    elapsed = end - start
    #print "finding salient colors took: " , elapsed
    if most_salient_colors != None:
        blank_image = numpy.zeros((200*size_factor,200*size_factor,3), numpy.float32)
        blank_image[:,0:25*size_factor] = most_salient_colors[0] if most_salient_colors[0] != None else [100,0,0]
        blank_image[:,25*size_factor:50*size_factor] = most_salient_colors[1] if most_salient_colors[1] != None else [100,0,0]
        blank_image[:,50*size_factor:75*size_factor] = most_salient_colors[2] if most_salient_colors[2] != None else [100,0,0]
        blank_image[:,75*size_factor:100*size_factor] = most_salient_colors[3] if most_salient_colors[3] != None else [100,0,0]
        blank_image[:,100*size_factor:125*size_factor] = most_salient_colors[4] if most_salient_colors[4] != None else [100,0,0]
        blank_image[:,125*size_factor:150*size_factor] = most_salient_colors[5] if most_salient_colors[5] != None else [100,0,0]
        blank_image[:,150*size_factor:175*size_factor] = most_salient_colors[6] if most_salient_colors[6] != None else [100,0,0]
        blank_image[:,175*size_factor:200*size_factor] = most_salient_colors[7] if most_salient_colors[7] != None else [100,0,0]
        high_sal_image = numpy.asarray(numpy.round(numpy.asarray(cv2.cvtColor(blank_image, cv2.COLOR_LAB2BGR)) * 255, 0), numpy.uint8)
    
    if least_salient_colors != None:
        blank_image = numpy.zeros((200*size_factor,200*size_factor,3), numpy.float32)
        blank_image[:,0:25*size_factor] = least_salient_colors[0] if least_salient_colors[0] != None else [100,0,0]
        blank_image[:,25*size_factor:50*size_factor] = least_salient_colors[1] if least_salient_colors[1] != None else [100,0,0]
        blank_image[:,50*size_factor:75*size_factor] = least_salient_colors[2] if least_salient_colors[2] != None else [100,0,0]
        blank_image[:,75*size_factor:100*size_factor] = least_salient_colors[3] if least_salient_colors[3] != None else [100,0,0]
        blank_image[:,100*size_factor:125*size_factor] = least_salient_colors[4] if least_salient_colors[4] != None else [100,0,0]
        blank_image[:,125*size_factor:150*size_factor] = least_salient_colors[5] if least_salient_colors[5] != None else [100,0,0]
        blank_image[:,150*size_factor:175*size_factor] = least_salient_colors[6] if least_salient_colors[6] != None else [100,0,0]
        blank_image[:,175*size_factor:200*size_factor] = least_salient_colors[7] if least_salient_colors[7] != None else [100,0,0]
        low_sal_image = numpy.asarray(numpy.round(numpy.asarray(cv2.cvtColor(blank_image, cv2.COLOR_LAB2BGR)) * 255, 0), numpy.uint8)
    
    
    sal_map = sal_map.swapaxes(1,0)
    
    
    sal_map = cv2.cvtColor(sal_map, cv2.COLOR_LAB2BGR)
    sal_map = numpy.asarray(numpy.round(numpy.asarray(sal_map) * 255, 0), numpy.uint8)
    
    #remove 'None' colors
    if least_salient_colors != None:
        least_salient_colors = [least_salient_colors[i] for i in range(len(least_salient_colors)) if least_salient_colors[i] != None]
    if most_salient_colors != None:
        most_salient_colors = [most_salient_colors[i] for i in range(len(most_salient_colors)) if most_salient_colors[i] != None]
    dom_colors = [vivImage.dominant_colors[i] for i in range(len(vivImage.dominant_colors)) if vivImage.dominant_colors[i] != None]
    
    if least_salient_colors != None:
        distinct_colors = Utilities.removeSimilarColors(numpy.concatenate((dom_colors,most_salient_colors,least_salient_colors)))
        vivImage.least_sal_colors = Utilities.removeSimilarColors(least_salient_colors)
        vivImage.most_sal_colors = Utilities.removeSimilarColors(most_salient_colors)
        vivImage.dom_colors = Utilities.removeSimilarColors(dom_colors)
    elif most_salient_colors != None:
        distinct_colors = Utilities.removeSimilarColors(numpy.concatenate((dom_colors,most_salient_colors)))
        vivImage.most_sal_colors = Utilities.removeSimilarColors(most_salient_colors)
        vivImage.most_sal_colors = Utilities.removeSimilarColors(most_salient_colors)
        vivImage.dom_colors = Utilities.removeSimilarColors(dom_colors)
    else:
        distinct_colors = Utilities.removeSimilarColors(dom_colors)
        vivImage.dom_colors = Utilities.removeSimilarColors(dom_colors)
    vivImage.distinctColors = distinct_colors
    color_width = 25 * size_factor
    window_size = color_width * len(distinct_colors)
    blank_image = numpy.zeros((200*2,window_size, 3), numpy.float32)
    for color_index in range(len(distinct_colors)):
        blank_image[:,color_index*color_width:(color_index+1)*color_width] = distinct_colors[color_index]
    distinct_color_image = numpy.asarray(numpy.round(numpy.asarray(cv2.cvtColor(blank_image, cv2.COLOR_LAB2BGR)) * 255, 0), numpy.uint8)
    
    '''
    Canny edge detection
    '''
    start = time.clock()
    edgeImage = vivImage.lineDetection()
    end = time.clock()
    elapsed = end - start
    #print "Canny edge detection took: " , elapsed
    #print "#######################"
    
    #cv2.imwrite('saliency.jpg', sal_map)
    #cv2.imwrite('salient_colors.jpg', high_sal_image)
    #sal_map = cv2.imread('saliency.jpg')
    #cv2.imshow('distinct_colors', distinct_color_image)
    #cv2.imshow('saliency_map',sal_map)
    #cv2.imshow('salient_colors', high_sal_image)
    #cv2.imshow('low_salience_colors', low_sal_image)
    #cv2.imshow('canny_edge_detection', edgeImage)
    #cv2.waitKey(0)
    #cv2.destroyAllWindows()
    '''
    im = Image.open(fileName)
    vivImage = VIVImage()
    vivImage.setPixelData(im)
    #print im.mode
    #print "====================================================================================================================="
    rgb2xyz = (
    0.412453, 0.357580, 0.180423, 0,
    0.212671, 0.715160, 0.072169, 0,
    0.019334, 0.119193, 0.950227, 0 )
    #im2 = im.convert("RGB", rgb2xyz)
    #vivImage.setPixelData(im, "XYZ")
    
    #im.show()
    '''
    return vivImage
