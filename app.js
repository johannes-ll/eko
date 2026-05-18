// Vad som ska finnas med i aktiviteter:
// Plats, medlemmar, skapare av aktivitet, väder
// Väder vill vi uppdatera om de inte uppdaterats på ett tag.
// Användare frågar om alla aktiviteter -> frågar om info om specifik aktivitet
// Vi lagrar temperatur med tidsstämpel, om det gått en timme från senaste uppdatering så uppdaterar vi info med nytt väder innan vi ger tillbaka till användare

let activities = [
    {
        id: 1,
        name: "Picknick i parken",
        creatorid: 1,
        loc: "Stadsparken",
        members: 20,
        desc:"Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, fugiat recusandae tenetur at rem quis explicabo eum voluptatum nam fuga porro alias, culpa velit. Dolores voluptatum et quisquam? Nobis ea beatae, vero rerum ex deserunt, debitis adipisci voluptates, illo facilis dolorem nesciunt maiores quod numquam fugiat officiis quasi error eveniet facere. Eaque consequuntur doloribus quisquam eveniet vitae explicabo veniam, voluptate, voluptatum fuga, nisi ducimus cumque eius enim modi laboriosam nam quos dignissimos optio? Id modi tempore aspernatur illo autem voluptates. Nulla delectus iste voluptas nihil deserunt voluptates non, eos temporibus esse commodi dolorem consequuntur beatae error dolores sapiente itaque possimus.",
        weather: { temp: 20, condition: "sunny" }
    },
    {
        id: 2,
        name: "Kvällspromenad",
        creatorid: 2,
        loc: "Åpromenaden",
        members: 8,
        desc:"",
        weather: { temp: 14, condition: "cloudy" }
    },
    {
        id: 3,
        name: "Löpning",
        creatorid: 3,
        loc: "Elljusspåret",
        members: 12,
        desc:"",
        weather: { temp: 11, condition: "rainy" }
    },
    {
        id: 4,
        name: "Skridskor",
        creatorid: 4,
        loc: "Isbanan",
        members: 6,
        desc:"",
        weather: { temp: -2, condition: "snowy" }
    },
    {
        id: 5,
        name: "Cykeltur",
        creatorid: 5,
        loc: "Gamla Uppsala",
        members: 10,
        desc:"",
        weather: { temp: 17, condition: "windy" }
    }
]

function getWeatherEmoji(condition) {
    switch (condition) {
        case "sunny":
            return "☀️"
        case "partly_cloudy":
            return "🌤️"
        case "cloudy":
            return "☁️"
        case "rainy":
            return "🌧️"
        case "storm":
            return "⛈️"
        case "snowy":
            return "🌨️"
        case "foggy":
            return "🌫️"
        case "windy":
            return "💨"
        default:
            return "🌍"
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
    title.textContent = activity.name

    const link = document.createElement("a");
    link.href = ""

    const addressSpan = document.createElement("span")
    addressSpan.textContent = activity.loc + " "

    const goingText = document.createTextNode(`${activity.members} going`)

    link.appendChild(addressSpan)
    link.appendChild(goingText)

    infoDiv.appendChild(title)
    infoDiv.appendChild(link)

    const weatherDiv = document.createElement("div")
    weatherDiv.className = "weather"

    const emoji = document.createElement("span")
    emoji.className = "weather-icon"
    emoji.textContent = getWeatherEmoji(activity.weather.condition)

    const temp = document.createElement("p")
    temp.className = "temp"
    temp.textContent = `${activity.weather.temp}`

    weatherDiv.appendChild(emoji)
    weatherDiv.appendChild(temp)

    activityDiv.appendChild(infoDiv)
    activityDiv.appendChild(weatherDiv)

    return activityDiv
}

function update_list(a) {
    document.querySelector("header").style.display = "flex"
    const list = document.querySelector(".list")
    list.replaceChildren()

    if (!a)
        a = activities

    a.forEach(activity => {
        const card = createActivityCard(activity)
        list.appendChild(card)
    })
}

update_list()

function show_activity(id) {

    document.querySelector("header").style.display = "none"
    const activity = activities.find(item => item.id === id)
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

    const temp = document.createElement("a")
    temp.className = "temp"
    temp.href = ""
    temp.textContent = `${getWeatherEmoji(activity.weather.condition)} ${activity.weather.temp}`

    const title = document.createElement("h1")
    title.textContent = activity.name

    banner.appendChild(temp)
    banner.appendChild(title)

    const content = document.createElement("div")
    content.className="content"

    const members = document.createElement("h2")
    members.textContent = `${activity.members} members going`

    const location = document.createElement("p")
    location.textContent = `Plats: ${activity.loc}`

    const creator = document.createElement("p")
    creator.textContent = `Skapare ID: ${activity.creatorid}`

    const description = document.createElement("p")
    description.textContent = activity.desc

    const button = document.createElement("button")
    button.textContent = "Gå med i aktivitet!"

    const backButton = document.createElement("button")
    backButton.textContent = "Tillbaka"
    backButton.addEventListener("click", () => update_list())

    
    
    const buttondiv = document.createElement("div")
    buttondiv.className = "btns"
    buttondiv.appendChild(button)
    buttondiv.appendChild(backButton)


    fetch(`get_delete_button.php?authorId=${activity.creatorid}`)
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