
//jQuery(document).ready(function() {
	// we use the listeners to resize stuff also for responsive themes
        // but resize is triggered on scrolldown/up too, so we don't use it
	// google.maps.event.addDomListener(window, 'resize', resizeGMap);
//	google.maps.event.addDomListener(window, 'load', loadGMap);
//});

jQuery(document).ready(function($) {
	// first the global map (if present)
	var divs = document.getElementsByTagName('div');
	// create the tile layer with correct attribution
	var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	var osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
	var div_arr_map = new Array();
	for (var i = 0; i < divs.length; i++) {
		var divname = divs[i].id; 
		if (divname.indexOf("eme_global_map_") === 0) { 
			var map_id = divname.replace("eme_global_map_","");
			var data = window['global_map_info_'+map_id];
			var marker_clustering = data.marker_clustering;
			var locations = data.locations;
			var markersList = new Array();
			var max_latitude = -500.1;
			var min_latitude = 500.1;
			var max_longitude = -500.1;
			var min_longitude = 500.1;

			var default_map_icon=data.default_map_icon;
			var zoom_factor=parseInt(data.zoom_factor);
			var enable_zooming=false;
			var letter_icons=false;
			var gestures=false;
			if (data.letter_icons === 'true') {
				letter_icons = true;
			}
			if (data.enable_zooming === 'true') {
				enable_zooming = true;
			}
			if (data.gestures === 'true') {
				gestures = true;
			}

			$.each(locations, function(i, item) {
				if (parseFloat(item.location_latitude) > max_latitude) {
					max_latitude = parseFloat(item.location_latitude);
				}
				if (parseFloat(item.location_latitude) < min_latitude) {
					min_latitude = parseFloat(item.location_latitude);
				}
				if (parseFloat(item.location_longitude) > max_longitude) {
					max_longitude = parseFloat(item.location_longitude);
				}
				if (parseFloat(item.location_longitude) < min_longitude) {
					min_longitude = parseFloat(item.location_longitude); 
				}
			});

			center_lat = min_latitude + (max_latitude - min_latitude)/2;
			center_lon = min_longitude + (max_longitude - min_longitude)/2;
			//console.log("center: " + center_lat + " - " + center_lon) + min_longitude;

			lat_interval = max_latitude - min_latitude;

			//vertical compensation to fit in the markers
			vertical_compensation = lat_interval * 0.1;

			// we don't use an initial zoom level, later on we zoom using fitbounds to show all locations at max allowed zoom level
			var myOptions = {
				center: L.latLng(center_lat + vertical_compensation,center_lon),
				doubleClickZoom: false,
				scrollWheelZoom: enable_zooming,
				gestureHandling: gestures
			};
			// in JS: var keeps the scope of a variable to the FUNCTION, not just the LOOP
			// so: we can't just reuse the same name here (like e.g.: var mymap=L.map ....,
			// since "mymap" was already used in the loop before and adding "var" does not reinit the variable)
			// The simple solution: use an array to store your stuff
			div_arr_map[i] = L.map(divname, myOptions);
			// add the title layer, we also add a class that can switch to darkmode (css) if needed
			L.tileLayer(osmUrl, {attribution: osmAttrib, className: 'eme-map-tiles'}).addTo(div_arr_map[i]);
			// if a popup contains an image, the size might be wrong, try to rectify with a popup update
			// based on https://stackoverflow.com/questions/38170366/leaflet-adjust-popup-to-picture-size
			div_arr_map[i].on("popupopen", function(e) {
				$(".leaflet-popup-content img:last").one("load", function() {
					e.popup.update();
				});
			});

			if (marker_clustering == 'true') {
				var markers = L.markerClusterGroup();
			}

			$.each(locations, function(index, item) {
				var letter;
				var myIcon;
				if (index>25) {
					var rest=index%26;
					var firstindex=Math.floor(index/26)-1;
					letter = String.fromCharCode("A".charCodeAt(0) + firstindex)+String.fromCharCode("A".charCodeAt(0) + rest);
				} else {
					letter = String.fromCharCode("A".charCodeAt(0) + index);
				}

				// create custom marker icons using the letter(s) above
				if (letter_icons) {
					myIcon = L.divIcon({className: 'eme-map-marker', iconSize: [28,28], html: letter});
				} else {
					if (item.map_icon!='') {
						myIcon = L.icon({iconUrl: item.map_icon, iconSize:[32,32],iconAnchor:[16,32],popupAnchor:[1,-28],tooltipAnchor:[16,-24]});
					} else if (default_map_icon!='') {
						myIcon = L.icon({iconUrl: default_map_icon, iconSize:[32,32],iconAnchor:[16,32],popupAnchor:[1,-28],tooltipAnchor:[16,-24]});
					} else {
						myIcon = new L.Icon.Default();
					}
				}

				var point = L.latLng(parseFloat(item.location_latitude), parseFloat(item.location_longitude));
				var balloon_content = "<div class='eme-location-balloon'>"+eme_htmlDecode(item.location_balloon)+"</div>";
                                var marker = L.marker(point,{icon: myIcon});
                        	marker.bindPopup(balloon_content, { maxWidth: 1600 });
				if (marker_clustering == 'true' ) {
					markers.addLayer(marker);
				} else {
					marker.addTo(div_arr_map[i]);
				}
				// Add to list of markers
				markersList[item.location_id] = marker;
				if ($('li#location-'+item.location_id+"_"+map_id).length) {
					$('li#location-'+item.location_id+"_"+map_id+' a').on('click',function() {
						if (marker_clustering == 'true' ) {
							var m = markersList[item.location_id];
							markers.zoomToShowLayer(m, function() {
								m.openPopup();
							});
						} else {
							marker.openPopup();
						}
					});
				}
			});
			if (marker_clustering == 'true') {
				markers.addTo(div_arr_map[i]);
			}
			// now zoom the map to a level that shows all markers at max zoom level
			div_arr_map[i].fitBounds([
				[min_latitude,min_longitude],
				[max_latitude,max_longitude]
			]);
		}
	
		// and now for the normal maps (if any)
		if(divname.indexOf("eme-location-map_") === 0) { 

			lat_id=parseFloat($(divs[i]).data('lat'));
			lon_id=parseFloat($(divs[i]).data('lon'));
			map_icon=$(divs[i]).data('map_icon');
			default_map_icon=$(divs[i]).data('default_map_icon');
			var mapCenter= L.latLng(lat_id+0.005,lon_id-0.003);
			var myOptions = {
				zoom: $(divs[i]).data('zoom_factor'),
				center: mapCenter,
				doubleClickZoom: false,
				scrollWheelZoom: $(divs[i]).data('enable_zooming'),
				gestureHandling: $(divs[i]).data('gestures')
			};
			if (map_icon!='') {
				myIcon = L.icon({iconUrl: map_icon, iconSize:[32,32],iconAnchor:[16,32],popupAnchor:[1,-28],tooltipAnchor:[16,-24]});
			} else if (default_map_icon!='') {
				myIcon = L.icon({iconUrl: default_map_icon, iconSize:[32,32],iconAnchor:[16,32],popupAnchor:[1,-28],tooltipAnchor:[16,-24]});
			} else {
				myIcon = new L.Icon.Default();
			}
			// in JS: var keeps the scope of a variable to the FUNCTION, not just the LOOP
			// so: we can't just reuse the same name here (like e.g.: var mymap=L.map ....,
			// since "mymap" was already used in the loop before and adding "var" does not reinit the variable)
			// The simple solution: use an array to store your stuff
			div_arr_map[i] = L.map(divname, myOptions);
			// add the title layer, we also add a class that can switch to darkmode (css) if needed
			L.tileLayer(osmUrl, {attribution: osmAttrib, className: 'eme-map-tiles'}).addTo(div_arr_map[i]);
			// if a popup contains an image, the size might be wrong, try to rectify with a popup update
			// based on https://stackoverflow.com/questions/38170366/leaflet-adjust-popup-to-picture-size
			div_arr_map[i].on("popupopen", function(e) {
				$(".leaflet-popup-content img:last").one("load", function() {
					e.popup.update();
				});
			});
			// define the popup and marker
			var s_popcontent = "<div class='eme-location-balloon'>"+$(divs[i]).data('map_text')+"</div>";
                        var s_marker = L.marker(L.latLng(lat_id, lon_id), {icon: myIcon}).addTo(div_arr_map[i]);
			// now show the markter and popup
                        s_marker.bindPopup(s_popcontent, { maxWidth: 1600 }).openPopup();
		}
	}
});
