<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="c">
        <header>
            <h1>Fly Från Eko</h1>
            <div>
                <input type="search" name="" id="">
                <a href="">Skapa</a>
            </div>
        </header>
        <div class="list">
            <div class="activity">
                <div>
                    <h2>Activity name</h2>
                    <a href=""><span>adress</span>20 going</a>
                </div>
                <div class="weather">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-sun" viewBox="0 0 16 16">
                    <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6m0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708"/>
                    </svg>
                    <p href="" class="temp">20</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Vad som ska finnas med i aktiviteter:
        // Plats, medlemmar, skapare av aktivitet, väder
        // Väder vill vi uppdatera om de inte uppdaterats på ett tag.
        // Användare frågar om alla aktiviteter -> frågar om info om specifik aktivitet
        // Vi lagrar temperatur med tidsstämpel, om det gått en timme från senaste uppdatering så uppdaterar vi info med nytt väder innan vi ger tillbaka till användare

        let activities = [
            {id:1, name:"activity", creatorid:1, loc:"Adress", members:"referns till lista", weather:{temp:20, condition:"sunny"}}
        ]

        function update_list {

        }
    </script>
</body>
</html>