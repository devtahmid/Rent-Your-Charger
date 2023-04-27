
// Creating map options
import {TileLayer} from "./lib/leaflet-src.esm";

var mapOption={center:[26.106, 50.57],
    zoom:10

}
var map=new L.map('map',mapOption)
var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
map.addLayer(layer);