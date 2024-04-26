if [ -x "$(command -v wget)" ]; then
  wget http://s3.amazonaws.com/georaster/iso.xml -O iso.xml
  wget http://s3.amazonaws.com/georaster/geonode_atlanteil.tif -O geonode_atlanteil.tif
  wget http://s3.amazonaws.com/georaster/rgb_raster.tif -O rgb_raster.tif
elif [ -x "$(command -v curl)" ]; then
  curl http://s3.amazonaws.com/georaster/iso.xml --output iso.xml
  curl http://s3.amazonaws.com/georaster/geonode_atlanteil.tif --output geonode_atlanteil.tif
  curl http://s3.amazonaws.com/georaster/rgb_raster.tif --output rgb_raster.tif
fi
