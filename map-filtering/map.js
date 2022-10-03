const mapContainer = document.querySelector('#map');
const filterRadios = document.querySelectorAll('.map__filter-radio');

// Check if mapcontainer is found
if (mapContainer !== null && mapContainer !== undefined) initMap();

function initMap() {
  let lat = 65;
  let lng = 30;
  const locations = JSON.parse(site.locations);
  const svgPath = `${site.theme_dir}/svg/marker-`; // set file path for marker icon files
  let infoWindow = new google.maps.InfoWindow();
  let markerCluster = null;

  const map = new google.maps.Map(mapContainer, {
    zoom: 5,
    center: {
      lat: lat,
      lng: lng
    },
    disableDefaultUI: true,
    zoomControl: true,
    fullscreenControl: false
  });

  const clusteredMarkes = [];
  const highlightedMarkers = [];

  // Set custom marker icons
  const markerIcons = {
    member: `${svgPath}member.svg`,
    partner: `${svgPath}partner.svg`,
  };

  // Go through each reference and get reference data
  const markers = locations.map((member, i) => {
    const KEYNAME = 0;
    const KEYTYPE = 1;
    const KEYLAT = 2;
    const KEYLONG = 3;
    const KEYTEXT = 4
    const KEYWEBSITE = 5
    const KEYHIGHLIGHTED = 6;
    const memberLat = member[KEYLAT];
    const memberLng = member[KEYLONG];
    const memberType = member[KEYTYPE] ? (member[KEYTYPE]).toString().trim().toLowerCase() : ''; // if there is no icon, add empty string
    const memberName = member[KEYNAME] ? member[KEYNAME] : ''; // if there is no name, add empty string
    const memberText = member[KEYTEXT] ? member[KEYTEXT] : null; // if there is no address, add empty string
    const memberWebsite = member[KEYWEBSITE] ? member[KEYWEBSITE] : null; // if there is no website, add empty string
    const memberHighlighted = member[KEYHIGHLIGHTED] ? member[KEYHIGHLIGHTED] : false;

    let markerOptions = {
      title: memberName,
      text: memberText,
      website: memberWebsite,
      position: {
        lat: parseFloat(memberLat),
        lng: parseFloat(memberLng),
      },
      category: memberType,
      isHighlighted: memberHighlighted,
    };

    // Check if member has icon defined
    // If member has icon, add custom icon from markerIcons array
    if (memberType !== '') {
      markerOptions.icon = markerIcons[memberType];
    }

    const marker = new google.maps.Marker(markerOptions);

    // Add event listener for each marker
    // Add member title as infowindow content
    google.maps.event.addListener(marker, 'click', (function (marker, i) {
      return () => {
        let windowContent = `<h2 class="marker__title">${marker.title}</h2>`;

        if (marker.text) {
          windowContent += `<div class="marker__text">${marker.text}</div>`;
        } else {
          windowContent += marker.website ? `<p class="marker__website"><a href="${marker.website}">${marker.website}</a></p>` : '';
        }

        infoWindow.setContent(windowContent);
        infoWindow.open(map, marker);
      }
    })(marker, i));


    if (marker.isHighlighted) {
      highlightedMarkers.push(marker);
    } else {
      clusteredMarkes.push(marker);
    }

    return marker;
  });

  for (const marker of highlightedMarkers) marker.setMap(map);

  markerCluster = new MarkerClusterer(map, clusteredMarkes, {
    styles: [{
      url: `${svgPath}clusterer-s.svg`,
      width: 76,
      height: 76,
      textColor: '#fff',
      textSize: 14,
      textLineHeight: 76,
    }, {
      url: `${svgPath}clusterer-xl.svg`,
      width: 96,
      height: 96,
      textColor: '#fff',
      textSize: 14,
      textLineHeight: 96,
    }],
    gridSize: 60,
    minimumClusterSize: 2
  });

  // Add event listener for radio buttons for filtering
  for (const radio of filterRadios) {
    radio.addEventListener('change', (e) => {
      const checkedRadio = e.target;
      const category = checkedRadio.value;
      let visibleMarkers = [];
      markerCluster.clearMarkers();

      for (const marker of markers) {
        const visible = category == 'all' ? true : marker.category == category;

        if (visible) {
          visibleMarkers.push(marker);
          marker.setMap(map);
        } else {
          marker.setMap(null);
        }
      }
      markerCluster.addMarkers(visibleMarkers);
    });
  }
}