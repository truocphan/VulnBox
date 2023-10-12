/* Code based on Google Map APIv3 Tutorials */

var gmapdata = new Array();
var gmapmarker = new Array();

function if_gmap_init(id) {
  map = document.getElementById(id + "_elementform_id_temp");
  var def_zoomval = parseInt(map.getAttribute("zoom"));
  var def_longval = map.getAttribute("center_x");
  var def_latval = map.getAttribute("center_y");

  var curpoint = new google.maps.LatLng(def_latval, def_longval);

  gmapdata[id] = new google.maps.Map(document.getElementById(id + "_elementform_id_temp"), {
    center: curpoint,
    zoom: def_zoomval,
    mapTypeId: 'roadmap'
  });

  google.maps.event.addListener(gmapdata[id], 'zoom_changed', function () {
    document.getElementById(id + "_elementform_id_temp").setAttribute("zoom", gmapdata[id].getZoom());
  });

  gmapmarker[id] = new Array();

  return false;
}

function update_position(id, i) {
  var longval = document.getElementById("longval" + i).value;
  var latval = document.getElementById("latval" + i).value;
  if (longval.length > 0) {
    if (isNaN(parseFloat(longval)) == true) {
      longval = 2.294254;
    } // end of if
  }
  else {
    longval = 2.294254;
  } // end of if

  if (latval.length > 0) {
    if (isNaN(parseFloat(latval)) == true) {
      latval = 48.858334;
    } // end of if
  }
  else {
    latval = 48.858334;
  } // end of if
  var curpoint = new google.maps.LatLng(latval, longval);

  gmapmarker[id][i].setPosition(curpoint);
  gmapdata[id].setCenter(curpoint);

  cur_zoom = gmapdata[id].getZoom();

  gmapdata[id].setZoom(cur_zoom);

  geocoder = new google.maps.Geocoder();

  geocoder.geocode({'latLng': gmapmarker[id][i].getPosition()}, function (results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        if (document.getElementById("addrval" + i))
          document.getElementById("addrval" + i).value = results[0].formatted_address;
      }
    }
  });

  var map = document.getElementById(id + "_elementform_id_temp");

  map.setAttribute("long" + i, longval);
  map.setAttribute("lat" + i, latval);

  return false;
}

function	remove_marker(id,i) {
  gmapmarker[id][i].setMap(null);
}

function add_marker_on_map(id, i, w_long, w_lat, w_info, dragb) {
  map = document.getElementById(id + "_elementform_id_temp");
  if (w_long == null) {
    var marker_point = gmapdata[id].getCenter();
    w_lat = gmapdata[id].getCenter().lat();
    w_long = gmapdata[id].getCenter().lng();
  }
  else
    var marker_point = new google.maps.LatLng(w_lat, w_long);

  geocoder = new google.maps.Geocoder();

  gmapmarker[id][i] = new google.maps.Marker({
    map: gmapdata[id],
    position: marker_point,
    draggable: dragb
  });

  gmapmarker[id][i].setDraggable(dragb);

  infoW = new google.maps.InfoWindow;

  google.maps.event.addListener(gmapdata[id], 'mouseover', function (event) {
    if (!document.getElementById("longval" + i)) {
      gmapmarker[id][i].setDraggable(false);
    }
  });

  google.maps.event.addListener(gmapmarker[id][i], 'drag', function () {
    if (document.getElementById("longval" + i)) {
      geocoder.geocode({'latLng': gmapmarker[id][i].getPosition()}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
            if (document.getElementById("addrval" + i))
              document.getElementById("addrval" + i).value = results[0].formatted_address;
          }
        }
      });

      map.setAttribute("long" + i, gmapmarker[id][i].getPosition().lng().toFixed(6));
      map.setAttribute("lat" + i, gmapmarker[id][i].getPosition().lat().toFixed(6));
      document.getElementById("latval" + i).value = gmapmarker[id][i].getPosition().lat().toFixed(6);
      document.getElementById("longval" + i).value = gmapmarker[id][i].getPosition().lng().toFixed(6);
    }
  });

  google.maps.event.addListener(gmapmarker[id][i], 'click', function () {
    infoW.setContent('<div style="overflow: hidden;">' + document.getElementById(id + "_elementform_id_temp").getAttribute('info' + i) + "</div>");
    var infoWOpt = {
      maxWidth: "300"
    };
    infoW.setOptions(infoWOpt);
    infoW.open(this.getMap(), this);
  });

  if (document.getElementById("longval" + i)) {
    document.getElementById("longval" + i).value = w_long;
    document.getElementById("latval" + i).value = w_lat;

    geocoder.geocode({'latLng': gmapmarker[id][i].getPosition()}, function (results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
          if (document.getElementById("addrval" + i)) document.getElementById("addrval" + i).value = results[0].formatted_address;
        }
      }
    });
    map.setAttribute("long" + i, w_long);
    map.setAttribute("lat" + i, w_lat);
  }
  return false;
} // end of if_gmap_init


function changeAddress(id, i) {
  var addrval = document.getElementById("addrval" + i).value;
  geocoder.geocode({'address': addrval}, function (results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      gmapdata[id].setCenter(results[0].geometry.location);
      gmapmarker[id][i].setPosition(results[0].geometry.location);
      document.getElementById("latval" + i).value = gmapmarker[id][i].getPosition().lat().toFixed(6);
      document.getElementById("longval" + i).value = gmapmarker[id][i].getPosition().lng().toFixed(6);
      map.setAttribute("long" + i, gmapmarker[id][i].getPosition().lng().toFixed(6));
      map.setAttribute("lat" + i, gmapmarker[id][i].getPosition().lat().toFixed(6));
    }
  });
}

function change_info(value,id,i) {
  map = document.getElementById(id + "_elementform_id_temp");
  map.setAttribute("info" + i, value);
}

function if_gmap_updateMap(id) {
  map = document.getElementById(id + "_elementform_id_temp");
  w_long = gmapdata[id].getCenter().lng();
  w_lat = gmapdata[id].getCenter().lat();
  map.setAttribute("center_x", w_long);
  map.setAttribute("center_y", w_lat);
}

