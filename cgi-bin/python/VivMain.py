#!/usr/bin/env python
'''
Created on Jan 13, 2014

@author: Britton
'''
import ImportImage
import AffectAnalysis as aa
import time
import sys
import CreateVase
import Utilities as util
import cv2
import cgi, cgitb

data = cgi.FieldStorage()
#data = "Bride.jpg"
#print sys.argv
if len(sys.argv) == 2:
    fileName = sys.argv[1]
else:
    fileName = r'C:\Misc\AnalysisPics\Sarah_0001.jpg'
if data != None:
    #fileName = r'C:\MyPrograms\ApacheServer\htdocs\upload-temp\%s' % data["image"].value
    fileName = r'C:\MyPrograms\ApacheServer\htdocs\upload-temp\Bride.jpg'
    #fileName = r'C:\MyPrograms\ApacheServer\htdocs\upload-temp\\' + data
print "fileName:",fileName
start = time.clock() 
image = ImportImage.importImage(fileName)
activity, warmth, heaviness, hardness = aa.analyzeAffectOfImage(image.distinctColors, None, None)
image.affectValues['activity'] = activity
image.affectValues['warmth'] = warmth
image.affectValues['heaviness'] = heaviness
image.affectValues['hardness'] = hardness
#print "image affect:",image.affectValues

candidate_vases = []
affect_differences = []
for i in range(10):
    candidate_vases.append(CreateVase.createVase())
    aa.analyzeAffectOfVase(candidate_vases[i])
    affect_differences.append(util.affectDifference(candidate_vases[i], image))
    #print "vase",i,"affect:",candidate_vases[i].affectValues
#vase = CreateVase.createVase()

smallest_difference_value = None
smallest_difference_index = None
for i in range(len(affect_differences)):
    #print "affect Difference:",affect_differences[i]
    if smallest_difference_value == None or affect_differences[i] < smallest_difference_value:
        smallest_difference_value = affect_differences[i]
        smallest_difference_index = i

#print "closest vase:",smallest_difference_index
#print "affect diff:",smallest_difference_value

vase_images= []
#for i in range(len(candidate_vases)):
#    vase_images.append(CreateVase.createImageWithText(candidate_vases[i].finalLeft, candidate_vases[i].finalRight, affect_differences[i]))
    
#for i in range(len(vase_images)):
#    cv2.imshow('rnd vase'+str(i), vase_images[i])
#cv2.waitKey(0)
#cv2.destroyAllWindows()

#print "(" + str(image.width) + "," + str(image.height) + ")"
#print len(image.pixel_data)

'''
for y in range(image.height):
    for x in range(image.width):
        print "(" + str(x) + "," + str(y) +")=" + str(image.lab_pixel_data[(x,y)]),
    print ""
'''
#l_hist = numpy.histogram(numpy.array(image.lab_pixel_data.values()[0]), range=(0,100.0))

#print l_hist

#for pixel in image.pixel_data:
#    print pixel

end = time.clock()
elapsed = end - start
#print "image loading took: " , elapsed

CreateVase.createAndWriteXML(0, 'False', candidate_vases[smallest_difference_index].finalRight, candidate_vases[smallest_difference_index].finalLeft)
