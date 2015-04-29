#!/usr/bin/env python
import CreateVase
import VaseCreation

vase = CreateVase.createVase()
VaseCreation.createAndWriteXML(1, True, vase.finalRight, vase.finalLeft)
