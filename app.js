// Vad som ska finnas med i aktiviteter:
// Plats, medlemmar, skapare av aktivitet, väder
// Väder vill vi uppdatera om de inte uppdaterats på ett tag.
// Användare frågar om alla aktiviteter -> frågar om info om specifik aktivitet
// Vi lagrar temperatur med tidsstämpel, om det gått en timme från senaste uppdatering så uppdaterar vi info med nytt väder innan vi ger tillbaka till användare

async function getEvents() {
    const response = await fetch("getEvents.php")
    const data = await response.json()

    return data
}

getEvents()

function getWeatherEmoji(code) {
    switch (code) {
        case 1: return "☀️"
        case 2:
        case 3: return "🌤️"
        case 4:
        case 5:
        case 6: return "☁️"
        case 7:
        case 8:
        case 9:
        case 10: return "🌫️"
        case 11:
        case 12:
        case 13:
        case 14:
        case 15:
        case 16:
        case 17: return "🌧️"
        case 18:
        case 19:
        case 20:
        case 21: return "🌨️"
        case 27: return "⛈️"
        default: return "🌍"
    }
}

function createActivityCard(activity) {
    const activityDiv = document.createElement("div")
    activityDiv.className = "card"

    activityDiv.addEventListener("click", (e) => {
        show_activity(activity.id)
    })

    const infoDiv = document.createElement("div")

    const title = document.createElement("h2")
    title.textContent = activity.title

    const link = document.createElement("a");
    link.href = ""

    const addressSpan = document.createElement("span")
    addressSpan.textContent = activity.adress + " "

    const goingText = document.createTextNode(`${activity.members} going`)

    link.appendChild(addressSpan)
    link.appendChild(goingText)

    infoDiv.appendChild(title)
    infoDiv.appendChild(link)
    activityDiv.appendChild(infoDiv)

    return activityDiv
}

async function update_list(a) {
    document.querySelector("header").style.display = "flex"

    const list = document.querySelector(".list")
    list.replaceChildren()

    if (!a) {
        a = await getEvents()
        console.log("was undefined")
    }

    console.log(a)

    a.forEach(activity => {
        const card = createActivityCard(activity)
        list.appendChild(card)
    })
}

update_list()
async function show_activity(id) {

    document.querySelector("header").style.display = "none"

    const activities = await getEvents()

    const activity = activities.find(item => item.id == id)

    const list = document.querySelector(".list")

    if (!activity) {
        const errorText = document.createElement("p")
        errorText.textContent = "Aktiviteten kunde inte hittas."
        list.replaceChildren(errorText)
        return
    }

    const activityDiv = document.createElement("div")
    activityDiv.className = "activity"

    const banner = document.createElement("div")
    banner.className = "banner"

    // const mapdiv = document.createElement("div")
    // mapdiv.id = "map"
    // mapdiv.class = "mazemap"

    // banner.appendChild(mapdiv)

    const temp = document.createElement("a")
    temp.className = "temp"
    temp.href = ""

    const rawDate = activity.date;   // "260520"
    const time = activity.time;      // "13:00"

    const day = rawDate.slice(0, 2)
    const month = rawDate.slice(2, 4)
    const year = "20" + rawDate.slice(4, 6)

    const isoString = new Date(`${year}-${month}-${day}T${time}:00`).toISOString()


    fetch(`getWeather.php?lat=${activity.latitude}&lon=${activity.longitude}&time=${isoString}`)
        .then(r => r.json())
        .then(data => {
            console.log(data)

            temp.textContent =
                `${getWeatherEmoji(data.code)} ${data.temp}`
        })

    const title = document.createElement("h1")
    title.textContent = activity.title

    banner.appendChild(temp)
    banner.appendChild(title)

    const content = document.createElement("div")
    content.className = "content"

    const members = document.createElement("h2")
    members.textContent = `${activity.members} members going`

    const location = document.createElement("p")
    location.textContent = `Plats: ${activity.adress}`

    const creator = document.createElement("p")
    creator.textContent = `Skapare ID: ${activity.userid}`

    const description = document.createElement("p")
    description.textContent = activity.info

    const button = document.createElement("button")
    button.textContent = "Gå med i aktivitet!"
    button.onclick = () => {
      window.location.href = `saveParticipants.php?id=${activity.eventID}`;
    }

    const backButton = document.createElement("button")
    backButton.textContent = "Tillbaka"
    backButton.addEventListener("click", () => update_list())

    const buttondiv = document.createElement("div")
    buttondiv.className = "btns"

    buttondiv.appendChild(button)
    buttondiv.appendChild(backButton)

    fetch(`get_delete_button.php?authorId=${activity.userid}`)
        .then(response => response.text())
        .then(html => {

            if (html.trim() !== "") {

                const wrapper = document.createElement("div")
                wrapper.innerHTML = html

                buttondiv.appendChild(wrapper.firstElementChild)
            }
        })

    activityDiv.appendChild(banner)

    content.appendChild(members)
    content.appendChild(location)
    content.appendChild(creator)
    content.appendChild(description)
    content.appendChild(buttondiv)

    activityDiv.appendChild(content)

    list.replaceChildren(activityDiv)

    // loadmap()
}

// eventlistnerer för vår sök
// vi vill söka efter varje input, köra update_list() med vår filtrerade lista
document.querySelector("#search").addEventListener("input", (e) => {
    const searchText = e.target.value.toLowerCase().trim()

    const filteredActivities = activities.filter(activity => {
        return activity.name.toLowerCase().includes(searchText) ||
               activity.loc.toLowerCase().includes(searchText) ||
               activity.desc.toLowerCase().includes(searchText)
    })

    update_list(filteredActivities)
})


function loadmap() {
    
      
      const startLng = 17.619748;
      const startLat = 59.859456;
      const startZ = 1;

        var map = new Mazemap.Map({
            container: 'map',
            campuses: 110,
            center: {lng: startLng, lat: startLat},
            zoom: 12,
            zLevel: startZ
        });

        var routeDrawer;
        var currentPopup;

        map.on('load', function() {
          routeDrawer = new Mazemap.AtoBTripBasicDrawer(map, {
            routeLineColorPrimary: '#0099EA', 
            showDirectionArrows: true
          });

          onMapLoad()

        });

        function onMapLoad() {
          var targetLngLat = {
            lat: 59.8978,
            lng: 17.6333
          };
          var targetZ = map.zLevel;

          if(currentPopup) {
            currentPopup.remove();
          }

          if(routeDrawer) {
            routeDrawer.clear();
          }

            var fromString = `${startLng},${startLat},${startZ}`;
            var toString = `${targetLngLat.lng},${targetLngLat.lat},${targetZ}`;

            const routeParams = {
              mode: "PEDESTRIAN",
              campusCollectionTag: "uu",
              fromLngLatZ: fromString,
              toLngLatZ: toString,
              lang: "sv"
            };

            Mazemap.Data.getAtoBTrip(routeParams)
            .then(function(trip) {
              routeDrawer.setAtoBTrip(trip);

            Mazemap.Data.getPoiAt(targetLngLat, targetZ)
            .then(poi => {
            var poiName = (poi && poi.properties && poi.properties.name) ? poi.properties.name : "Framme!";

              currentPopup = new Mazemap.Popup()
              .setLngLat(targetLngLat)
              .setHTML(`${poiName}`)
              .addTo(map);
            });
        });
        }
}