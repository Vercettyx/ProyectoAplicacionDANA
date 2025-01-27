function iniciarMap(){
    var coord = {lat:39.4296815 ,lng: -0.4143745};
    var map = new google.maps.Map(document.getElementById('map'),{
      zoom: 12,
      center: coord
    });
    var marker = new google.maps.Marker({
      position: coord,
      map: map
    });
}