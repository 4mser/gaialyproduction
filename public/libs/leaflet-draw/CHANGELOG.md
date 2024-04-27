Leaflet.draw Changelog
======================

## master

An in-progress version being developed on the master branch.

## 0.4.10 (July 3, 2017)

 * Locking Leaflet version to 0.7.x-1.0.x
 * Polygon line length, square kilometers and configurable precision
 * fix for _getMeasurementString
 * Locale number formatting added

## 0.4.2 (November 7, 2016)

### Improvements

 * Documentation is now automatically generated
 * L.Draw.Event is new, has all events in string format

### Bugfixes

 * Travis tests

## 0.4.1 (October 21, 2016)
 
### Bugfixes

 * fix markers becomming invisible when in edit mode
 * Fix linking for EditPolyOptions
 * Fixes very minor issue in readme with invalid variable name

## 0.4.0 (October 13, 2016)

### Improvements

 * Add support for Leaflet 1.0.0+

### Bugfixes

 * New L.Draw.Tooltip to mitigate L.Tooltip collision
 
### Potential Issues

 * Several namespace changes, see BREAKINGCHANGES.md
 * Cross support for both 0.7.0 and 1.0.0

## 0.3.0 (March 09, 2016)

### Improvements

 * Add support for touch devices
 * Corrected license
 * Linter

### Bugfixes

 * Added mouse handlers
 * Fixed event listener leaks in Polyline
 * Edit vertex event

## 0.2.4 (February 04, 2014)

### Improvements

 * Add support for toolbar touch styles. (by [@alanshaw](http://github.com/alanshaw)). [#269](http://github.com/Leaflet/Leaflet.draw/pull/269 )
 * Add support for maintaining a layers color when entering edit mode.
 * Add support for showing area when drawing a rectangle.
 * Refactor marker editing to bring in line with path editing handlers. Decouple setting editing style from edit toolbar. (by [@manleyjster](http://github.com/manleyjster)). [#323](http://github.com/Leaflet/Leaflet.draw/pull/323)
 * Prevent skewing on selected edit marker. (by [@kyletolle](http://github.com/kyletolle)). [#341](http://github.com/Leaflet/Leaflet.draw/pull/341)
 * Add support for changing the 'Radius' label text.

### Bugfixes

 * Fix deleted layers LayerGroup constructor type. (by [@w8r ](http://github.com/w8r )). [#334](http://github.com/Leaflet/Leaflet.draw/pull/334)

## 0.2.3 (January 14, 2014)

### Improvements

 * Restrict editing polygons so that at least 3 points are present. (by [@Zverik](http://github.com/Zverik)). [#200](http://github.com/Leaflet/Leaflet.draw/pull/200)
 * Tooltips initially start hidden until the mouse has been moved. (by [@Zverik](http://github.com/Zverik)). [#210](http://github.com/Leaflet/Leaflet.draw/pull/210)
 * Fixup spelling errors. (by [@nexushoratio](http://github.com/nexushoratio)). [#223](http://github.com/Leaflet/Leaflet.draw/pull/223)
 * Combine ie specific style within leaflet.draw.css stylesheet. (by [@frankrowe](http://github.com/frankrowe)). [#221](http://github.com/Leaflet/Leaflet.draw/pull/221)
 * Improve my terrible engrish. (by [@erictheise](http://github.com/erictheise)). [#237](http://github.com/Leaflet/Leaflet.draw/pull/237)
 * Fire `editstart` events when a poly or simple shape is initially edited. (by [@atombender](http://github.com/atombender)). [#245](http://github.com/Leaflet/Leaflet.draw/pull/245)
 * Add ability to add a new vertex to a polyline or polygon hander.
 * Added ability to remove/undo the last placed point for polyline or polygons. (by [@Zverik](http://github.com/Zverik)). [#242](http://github.com/Leaflet/Leaflet.draw/pull/242)
 * Dynamically position the action toolbars. (by [@DevinClark](http://github.com/DevinClark)). [#240](http://github.com/Leaflet/Leaflet.draw/pull/240)
 * Improve polyline/polygon drawing by accepting some motion on click. (by [@atombender](http://github.com/atombender)). [#249](http://github.com/Leaflet/Leaflet.draw/pull/249)
 * Only draw a limited number of guide dashes to improve performance in some rare cases. [#254](http://github.com/Leaflet/Leaflet.draw/pull/254)

### Bugfixes

 * Fix edit toolbar so diabled state is represented correctly. (by [@joeybaker](http://github.com/joeybaker)). [#203](http://github.com/Leaflet/Leaflet.draw/pull/203)
 * Fixed path middle marker positions. (by [@Zverik](http://github.com/Zverik)). [#208](http://github.com/Leaflet/Leaflet.draw/pull/208)
 * Fix issue where toolbar buttons would have focus after clicked so couldn't use escape to cancel until clicked map at least once.
 * Fix toolbar icons for retina displays. (by [@dwnoble](http://github.com/dwnoble)). [#217](http://github.com/Leaflet/Leaflet.draw/pull/217)
 * Ensure that options are not shared between draw handler classes. (by [@yohanboniface](http://github.com/yohanboniface)). [#219](http://github.com/Leaflet/Leaflet.draw/pull/219)
 * Fix bug where multiple handlers could be active. (by [@manubb](http://github.com/manubb)). [#247](http://github.com/Leaflet/Leaflet.draw/pull/247)

## 0.2.2 (October 4, 2013)

### Improvements

 * Refactored the `L.drawLocal' object to be better structured and use this object whereever text is used. *NOTE: THIS IS A NEW FORMAT, SO WILL BRESK ANY EXISTING CUSTOM `L.drawLocal` SETTINGS*.
 * Added Imperial measurements to compliment the existing Metric measurements when drawing a polyline or polygon.
 * Added `draw:editstart` and `draw:editstop` events. (by [@bhell](http://github.com/bhell)). [#175](http://github.com/Leaflet/Leaflet.draw/pull/175)
 * Added `repeatMode` option that will allow repeated drawing of features. (by [@jayhogan](http://github.com/jayhogan) and [@cscheid](http://github.com/cscheid)). [#178](http://github.com/Leaflet/Leaflet.draw/pull/178)
 * Added abilit to set circle radius measurement to imperial units.
 * Added disabled state for edit/delete buttons when no layers present. (inspired by [@snkashis](http://github.com/snkashis)). [#136](http://github.com/Leaflet/Leaflet.draw/pull/136)
 * Add `showLength` and `showRadius` options to circle and polyline. (by [@Zverik](http://github.com/Zverik)). [#195](http://github.com/Leaflet/Leaflet.draw/pull/195)
 * Add option to disable tooltips. (by [@Zverik](http://github.com/Zverik)). [#196](http://github.com/Leaflet/Leaflet.draw/pull/196)

### Bugfixes

 * Fixed bug where edit handlers could not be disabled.
 * Added support for displaying the toolbar on the right hand side of the map. (by [@paulcpederson](http://github.com/paulcpederson)). [#164](http://github.com/Leaflet/Leaflet.draw/pull/164)
 * Add flexible width action buttons. (by [@Grsmto](http://github.com/Grsmto)). [#181](http://github.com/Leaflet/Leaflet.draw/pull/181)
 * Check for icon existence before disabling edit state. (by [@tmcw](http://github.com/tmcw)). [#182](http://github.com/Leaflet/Leaflet.draw/pull/182)
 * Only update guideslines when guidelines are present. (by [@jayhogan](http://github.com/jayhogan)). [#188](http://github.com/Leaflet/Leaflet.draw/pull/188)
 * Fixes to localization code so it can be correctly set after files have been loaded.
 * Fix for firing `draw:edit` twice for Draw.SimpleShape. (by [@cazacugmihai](http://github.com/cazacugmihai)). [#192](http://github.com/Leaflet/Leaflet.draw/pull/192)
 * Fix last edit menu buttons from wrapping. (by [@moiarcsan](http://github.com/moiarcsan)). [#198](http://github.com/Leaflet/Leaflet.draw/pull/198)

## 0.2.1 (July 5, 2013)

### Improvements

 * `draw:edited` now returns a `FeatureGroup` of features edited. (by [@jmkelly](http://github.com/jmkelly)). [#95](http://github.com/Leaflet/Leaflet.draw/pull/95)
 * Circle tooltip shows the radius (in m) while drawing.
 * Added Leaflet version check to inform developers that Leaflet 0.6+ is required.
 * Added ability to finish drawing polygons by double clicking. (inspired by [@snkashis](http://github.com/snkashis)). [#121](http://github.com/Leaflet/Leaflet.label/pull/121)
 * Added test environment. (by [@iirvine](http://github.com/iirvine)). [#123](http://github.com/Leaflet/Leaflet.draw/pull/123)
 * Added `L.drawLocal` object to allow users to customize the text used in the plugin. Addresses localization issues. (by [@Starefossen](http://github.com/Starefossen)). [#87](http://github.com/Leaflet/Leaflet.draw/pull/87)
 * Added ability to disable edit mode path and marker styles. (inspired by [@markgibbons25](http://github.com/markgibbons25)). [#121](http://github.com/Leaflet/Leaflet.label/pull/137)
 * Added area calculation when drawing a polygon.
 * Polyline and Polygon tooltips update on click as well as mouse move.

### Bugfixes

 * Fixed issue where removing a vertex or adding a new one via midpoints would not update the edited state for polylines and polygons.
 * Fixed issue where not passing in the context to `off()` would result in the event from not being unbound.(by [@koppelbakje](http://github.com/koppelbakje)). [#95](http://github.com/Leaflet/Leaflet.draw/pull/112)
 * Fixed issue where removing the draw control from the map would result in an error.
 * Fixed bug where removing points created by dragging midpoints would cause the polyline to not reflect any newly created points.
 * Fixed regression where handlers were not able to be disabled.(by [@yohanboniface](http://github.com/yohanboniface)). [#139](http://github.com/Leaflet/Leaflet.draw/pull/139)
 * Fixed bug where L.Draw.Polyline would try to remove a non-existant handler if the user cancelled and the polyline only had a single point.

## 0.2.0 (February 20, 2013)

Major new version. Added Edit toolbar which allows editing and deleting shapes.

### Features

 * Consistant event for shape creation. (by [@krikrou](http://github.com/krikrou)). [#58](http://github.com/Leaflet/Leaflet.draw/pull/58)

### Bugfixes

 * Fixed adding markers over vector layers. (by [@Starefossen](http://github.com/Starefossen)). [#82](http://github.com/Leaflet/Leaflet.draw/pull/82)

## 0.1.7 (February 11, 2013)

 * Add sanity check for toolbar buttons when adding top and bottom classes. (by [@yohanboniface](http://github.com/yohanboniface)). [#60](http://github.com/Leaflet/Leaflet.draw/pull/60)

## 0.1.6 (January 17, 2013)

* Updated toolbar styles to be in line with the new Leaflet zoom in/out styles.

## 0.1.5 (December 10, 2012)

### Features

 * Added 'drawing-disabled' event fired on the map when a draw handler is disabled. (by [@ajbeaven](http://github.com/thegreat)). [#35](http://github.com/jacobtoye/Leaflet.draw/pull/35)
 * Added 'drawing' event fired on the map when a draw handler is actived. (by [@ajbeaven](http://github.com/thegreat)). [#30](http://github.com/jacobtoye/Leaflet.draw/pull/30)

### Bugfixes

 * Stopped L.Control.Draw from storing handlers in it's prototype. (by [@thegreat](http://github.com/thegreat)). [#37](http://github.com/jacobtoye/Leaflet.draw/pull/37)

## 0.1.4 (October 8, 2012)

### Bugfixes

 * Fixed a bug that would cause an error when creating rectangles/circles withought moving the mouse. (by [@inpursuit](http://github.com/inpursuit)). [#25](http://github.com/jacobtoye/Leaflet.draw/pull/25)
 * Fixed a bug that would cause an error when clicking a different drawing tool while another mode enabled. (by [@thegreat](http://github.com/thegreat)). [#27](http://github.com/jacobtoye/Leaflet.draw/pull/27)
 * Fixed control buttons breaking plugin in oldIE.
 * Fixed drawing polylines and polygons in oldIE.

## 0.1.3 (October 3, 2012)

### Bugfixes

 * Tip label will now show over vertex markers.
 * Added ability to draw on top of existing markers and vector layers.
 * Clicking on a map object that has a click handler no longer triggers the click event when in drawing mode.

## Pre-0.1.3

Check the commit history for changes previous to 0.1.3.
