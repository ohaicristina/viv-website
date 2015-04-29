#!/usr/bin/env python
'''
Created on Sep 19, 2014

@author: Britton Horn
'''
import CreateVase
import random
import numpy as np
from Vase import Vase
from VIVImage import VIVImage
import AffectAnalysis as aa
import Utilities as util
import time

def breed(parent1, parent2):
    child = Vase()
    pickHeight = random.random()
    pickDnaLen = random.random()
    pickNumVerts = random.random()
    pickLeftEdge = random.random()
    pickBasePts = random.random()
    child.numVertices = parent1.numVertices if (pickNumVerts > 0.5) else parent2.numVertices
    child.leftedgeX = parent1.leftedgeX if (pickLeftEdge > 0.5) else parent2.leftedgeX
    child.rightedgeX = child.leftedgeX * -1
    child.basePts = parent1.basePts if (pickBasePts > 0.5) else parent2.basePts
    if len(parent1.parentDNA) != 0 and len(parent2.parentDNA) != 0:
        child.parentDNA.append([parent1.parentDNA,parent2.parentDNA])
    elif len(parent1.parentDNA) != 0:
        child.parentDNA.append(parent1.parentDNA)
    elif len(parent2.parentDNA) != 0:
        child.parentDNA.append(parent2.parentDNA)
    #print "parent1 height:",parent1.initHeight
    #print "parent2 height:",parent2.initHeight
    #print "parent1 DNA:",parent1.DNA
    #print "parent2 DNA:",parent2.DNA
    
    child.initHeight = parent1.initHeight if (pickHeight > 0.5) else parent2.initHeight
    # mixing DNA
    if random.random() > 0.5:
        childDnaLen = len(parent1.DNA) if (pickDnaLen > 0.5) else len(parent2.DNA)
        for elem in range(childDnaLen):
            child.DNA.append(parent1.DNA[elem] if ((random.random() > 0.5 and elem < len(parent1.DNA)) or elem >= len(parent2.DNA)) else parent2.DNA[elem])
    # Concatenating DNA
    else:
        #print "concatenating"
        for elem in range(len(parent1.DNA)):
            child.DNA.append(parent1.DNA[elem])
        for elem in range(len(parent2.DNA)):
            child.DNA.append(parent2.DNA[elem])
    #print "child height:",child.initHeight
    #print "child DNA:",child.DNA
    child = CreateVase.createVaseFromDNA(child)
    return child
    
def mutate(original):
    #print "parent DNA:",original.DNA[:]
    child = Vase()
    child.numVertices = original.numVertices
    child.initHeight = original.initHeight
    child.DNA = original.DNA[:]
    child.parentDNA = original.parentDNA[:]
    child.parentDNA.append(original.DNA[:])
    child.leftedgeX = original.leftedgeX
    child.rightedgeX = original.rightedgeX
    child.basePts = original.basePts
    #print "child's parent DNA:",child.parentDNA[:]
    
    change = random.randint(1,4)
    operation = random.randint(1,4)
    random_side = random.randint(1,3)
    random_depth = random.randint(2,10)*2
    random_height = random.randint(2,15)
    random_scale = random.uniform(0.75, 0.9)
    random_depth = random_depth if random.randint(1,2) == 1 else random_depth*-1
    random_base_pts = random.randint(3,9)
    if random_base_pts == 9:
        random_base_pts = 32
    side = ''
    if random_side == 1:
        side = 'LEFT'
    if random_side == 2:
        side = 'RIGHT'
    if random_side == 3:
        side = 'BOTH'
    # append new random DNA element
    if change == 1:
        #print "appending random element"
        child.DNA.append(['squeeze', random_depth,random_height,side] if operation <= 3 else ['shorten', random_scale, side])
    # replace existing DNA element with random element
    if change == 2:
        if (len(child.DNA)-1 <= 0):
            child.DNA = []
            child.DNA.append(['squeeze', random_depth,random_height,side] if operation <= 3 else ['shorten', random_scale, side])
        else:
            el_removed = random.randint(0, len(child.DNA)-1)
            #print "replacing element:",el_removed
            child.DNA[el_removed] = ['squeeze', random_depth,random_height,side] if operation <= 3 else ['shorten', random_scale, side]
    # remove DNA element
    if change == 3:
        if(len(child.DNA)-1 <= 0):
            child.DNA = []
        else :
            el_removed = random.randint(0, len(child.DNA)-1)
            #print "removing element: ",el_removed
            child.DNA.pop(el_removed)
    # change number of points in base
    if change == 4:
        #print "changing number of points in base from",child.basePts,"to",random_base_pts
        while child.basePts == random_base_pts:
            random_base_pts = random.randint(3,9)
        child.basePts = random_base_pts
    child = CreateVase.createVaseFromDNA(child)
    return child
    '''    
    if random.random() > 0.2:
        v.squeeze(random.randint(2, 10)*2, random.randint(2, 15), getRandomSide())
    if random.random() > 0.5:
        v.squeeze(random.randint(2, 10)*2, random.randint(2, 15), getRandomSide())
            
    if random.random() > 0.2:
        v.pull(random.randint(2, 10)*2, random.randint(2, 15), getRandomSide())
        
    if random.random() > 0.5:
        v.shorten(random.uniform(0.75, 0.9), getRandomSide())
    '''    
def evolveVase(activity, weight, warmth, hardness, img_id):
    total_steps = 100
    vases_per_run = 100
    new_candidates = []
    start_time = time.clock()
    
    #evolve vases
    for step in range(total_steps): 
        #print "step"
        '''
        Create Initial Pool of Random Vases
        '''
        candidate_vases = new_candidates[:]
        affect_differences = []
        if step == 0:
            for i in range(vases_per_run):
                candidate_vases.append(CreateVase.createVase())
        for i in range(vases_per_run):
            aa.analyzeAffectOfVase(candidate_vases[i])
            affect_differences.append(util.affectDifferenceFromValues(candidate_vases[i], activity, weight, warmth, hardness))
        
        '''
        Rank by affect difference
        '''
        sorted_indices = np.argsort(affect_differences)
        
        '''
        Keep Top 10%
        '''
        start = 0
        new_candidates = []
        kept_amt = int(round(len(candidate_vases) * .1))
        for i in range(0,kept_amt):
            new_candidates.append(candidate_vases[sorted_indices[i]])
        
        '''
        Breed next 40%
        '''
        start = kept_amt
        bred_amt = int(round(len(candidate_vases) * .4))
        if bred_amt % 2 != 0:
            bred_amt += 1
        for i in range(start, start + (bred_amt), 2):
            new_candidates.append(breed(candidate_vases[sorted_indices[i]], candidate_vases[sorted_indices[i+1]]))
        
        '''
        Mutate remaining 50%
        '''
        start += bred_amt
        mutate_amt = int(round(len(candidate_vases) * .5))
        for i in range(start, start + mutate_amt):
            new_candidates.append(mutate(candidate_vases[sorted_indices[i]]))
        
        '''
        Fill in leftover spots with random vases
        '''
        start += mutate_amt
        filler_amt = len(candidate_vases) - len(new_candidates)
        for i in range(len(new_candidates), len(new_candidates) + filler_amt):
            new_candidates.append(CreateVase.createVase())
    
    #go through final candidates
    affect_differences = []
    for i in range(vases_per_run):
        aa.analyzeAffectOfVase(new_candidates[i])
        affect_differences.append(util.affectDifferenceFromValues(candidate_vases[i], activity, weight, warmth, hardness))
             
    sorted_indices = np.argsort(affect_differences)
    closest_vase = util.splineVase(new_candidates[sorted_indices[0]])
    end_time = time.clock()
    elapsed = end_time - start_time  
    #print "evolution time:",elapsed
    #CreateVase.drawAnalyzedVase(closest_vase)
    insert_id = CreateVase.createAndSaveWebVase(closest_vase, img_id)
    return closest_vase, insert_id
