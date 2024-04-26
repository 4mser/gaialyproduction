#!/usr/bin/env python
# coding: utf-8

import numpy as np
import json
import sys
import os

tsdk = os.path.abspath("./TSDK")
sys.path.insert(0,tsdk)
from thermal import Thermal

nameFile = sys.argv[1]
jsonFileName = sys.argv[2]
option = sys.argv[3]
libPath = sys.argv[4]

thermal = Thermal(
    dirp_filename=libPath+'/libdirp.so',
    dirp_sub_filename=libPath+'/libv_dirp.so',
    iirp_filename=libPath+'/libv_iirp.so',
    exif_filename=None,
    dtype=np.float32,
)

temperature = thermal.parse_dirp2(image_filename=nameFile,m2ea_mode=option)
temp2 = []
temp3 = []
listTemp = []
for row in temperature:
    temp2=[]
    temp3=[]
    for point in row:
        temp2 = point.item(0)
        temp3.append(round(temp2,1))
    listTemp.append(temp3)

temperature = listTemp
json_str = json.dumps(temperature)


with open(jsonFileName, 'w') as fp:
    fp.write(json_str)
