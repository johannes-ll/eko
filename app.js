// Vad som ska finnas med i aktiviteter:
// Plats, medlemmar, skapare av aktivitet, väder
// Väder vill vi uppdatera om de inte uppdaterats på ett tag.
// Användare frågar om alla aktiviteter -> frågar om info om specifik aktivitet
// Vi lagrar temperatur med tidsstämpel, om det gått en timme från senaste uppdatering så uppdaterar vi info med nytt väder innan vi ger tillbaka till användare


// vi skapar en konstant map som vi flyttar runt mellan en gömd container och vå banner, på de sättet slipper vi initialisera en ny map varje gång
// egentligen smartare att behålla allt på samma plats och gömma containern och sen bara updatera rutt samt resten av info, men då riskerar vi att få tomma fält om inf saknas någonstans.
const mzmap = document.createElement("div")
mzmap.id = "map"
mzmap.class = "mazemap"
document.querySelector(".hidden").appendChild(mzmap)

// vi introducerar vår globala aktivitetslista
var activities

async function getEvents() {
    const response = await fetch("getEvents.php")
    const data = await response.json()

    // vi sätter vår globala aktivitetslista varje gång vi frågar om events samt returnerar dem.
    activities = data
    return data
}

// vi frågar om en ws2 kod från smhi som representerar väder, här matchar vi koden med väderförhållande som representeras med en emoji.
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

// vår funktion som skapar varje barn i vår lista av activities. vi kör den här så många gånger som de finns activities i vår activities var.
function createActivityCard(activity) {
    const activityDiv = document.createElement("div")
    activityDiv.className = "card"

    // om vi klickar p activitien så körs show_activity.
    // vi tar med id som input så show activity vet vilken activity som sk visas.
    activityDiv.addEventListener("click", (e) => {
        show_activity(activity.id)
    })

    const infoDiv = document.createElement("div")

    const title = document.createElement("h2")
    title.textContent = activity.title

    const link = document.createElement("a")

    const addressSpan = document.createElement("span")
    addressSpan.textContent = activity.adress + " "

    const goingText = document.createTextNode(`${activity.members} going`)

    link.appendChild(addressSpan)
    link.appendChild(goingText)

    infoDiv.appendChild(title)
    infoDiv.appendChild(link)
    activityDiv.appendChild(infoDiv)

    // vi ger tillbaa hela vår div som vi skapat, vi sätter alltså inte ut något i den här funktionen.
    return activityDiv
}

// funktionen tar antingen en filtrerad lista som vi får frn vår sökbar eller inget alls. om inget fås med så laddar vi in alla activities.
async function update_list(a) {
    // vi flyttar på vår map till vår gömda div.
    document.querySelector(".hidden").appendChild(mzmap)
    // vi börjar visa vår header igen. den göms av show_activity()
    document.querySelector("header").style.display = "flex"

    const list = document.querySelector(".list")
    list.replaceChildren()

    // om vi inte kört funktionen med vår egna lista så tar vi den globala listan med activities.
    if (!a) {
        a = activities
        console.log("was undefined")
    }

    // loopar igenom listan och gör cards för varje barn.
    a.forEach(activity => {
        const card = createActivityCard(activity)
        list.appendChild(card)
    })
}

// när vi först laddar in sidan måste vi vänta på att getEvents har kört färdigt.
async function init() {
    await getEvents()
    update_list()
}

init()

// funktion som visar en individuell aktivitet. tar aktivitetens id med som input.
async function show_activity(id) {
    // vi tar bort headern.
    document.querySelector("header").style.display = "none"

    // letar reda på vilken activity vi syftar på med vårat id.
    const activity = activities.find(item => item.id == id)

    const list = document.querySelector(".list")

    // om den aktiviteten inte finns så ger vi error (sanity check, har adrig hänt.)
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

    const temp = document.createElement("a")
    temp.className = "temp"
    temp.href = ""

    // vi måste konvertera date från de skumma sättet som de sparas i db.
    const rawDate = activity.date;   // "260520"
    const time = activity.time;      // "13:00"

    const day = rawDate.slice(0, 2)
    const month = rawDate.slice(2, 4)
    const year = "20" + rawDate.slice(4, 6)

    const isoString = new Date(`${year}-${month}-${day}T${time}:00`).toISOString()


    // vi anropar getWeather med plats och tid och får tillbaka väder för den platsen och tiden.
    fetch(`getWeather.php?lat=${activity.latitude}&lon=${activity.longitude}&time=${isoString}`)
        .then(r => r.json())
        .then(data => {
            // vi skriver ut temeraturen och condition med hjälp av vår getWeatherEmoji() funktion.
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


    const months = [
        "Januari", "Februari", "Mars", "April",
        "Maj", "Juni", "Juli", "Augusti",
        "September", "Oktober", "November", "December"
    ]

    const readableDate = `${Number(day)} ${months[Number(month) - 1]}`

    const datediv = document.createElement("p")
    datediv.textContent = `Tid: ${readableDate} ${activity.time}`

    const description = document.createElement("p")
    description.textContent = activity.info

    // gå med knapp.
    const button = document.createElement("button")
    button.textContent = "Gå med i aktivitet!"
    button.onclick = () => {
      window.location.href = `saveParticipants.php?id=${activity.id}`
    }

    const backButton = document.createElement("button")
    backButton.textContent = "Tillbaka"
    backButton.addEventListener("click", () => update_list())

    const buttondiv = document.createElement("div")
    buttondiv.className = "btns"

    buttondiv.appendChild(button)
    buttondiv.appendChild(backButton)

    // vi vill bara visa delete knappen om användaren kan ta bort aktiviteten.
    fetch(`get_delete_button.php?authorId=${activity.userid}`)
        .then(response => response.text())
        .then(html => {

            if (html.trim() !== "") {

                const wrapper = document.createElement("div")
                wrapper.innerHTML = html

                buttondiv.appendChild(wrapper.firstElementChild)
            }
    })

    // styling för mazemap
    document.querySelector(".mapboxgl-canvas").style.width = "80vw"
    // vi flyttar map från den gömda diven till vår banner
    banner.appendChild(mzmap)
    activityDiv.appendChild(banner)

    // vi callar en gglobal setRoute funktion som finns i map.js
    window.setRoute({
        lat: activity.latitude,
        lng: activity.longitude
    })

    content.appendChild(members)    
    content.appendChild(location)
    content.appendChild(datediv)
    content.appendChild(description)
    content.appendChild(buttondiv)

    activityDiv.appendChild(content)

    list.replaceChildren(activityDiv)

    // behövs för att mazemap ska fatta att vi updaterat width på .mapboxgl-canvas
    window.dispatchEvent(new Event("resize"))
}
// eventlistnerer för vår sök
// vi vill söka efter varje input, köra update_list() med vår filtrerade lista
document.querySelector("#search").addEventListener("input", (e) => {
    const searchText = e.target.value.toLowerCase().trim()
    const filteredActivities = activities.filter(activity => {
        return activity.title.toLowerCase().includes(searchText) ||
               activity.adress.toLowerCase().includes(searchText) ||
               activity.info.toLowerCase().includes(searchText)
    })

    update_list(filteredActivities)
})