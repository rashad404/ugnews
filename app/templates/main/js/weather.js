const weatherData = { "coord": { "lon": 49.892, "lat": 40.3777 }, "weather": [{ "id": 800, "main": "Clear", "description": "aydın səma", "icon": "01n" }], "base": "stations", "main": { "temp": 9.03, "feels_like": 6.75, "temp_min": 9.03, "temp_max": 9.03, "pressure": 1026, "humidity": 76 }, "visibility": 10000, "wind": { "speed": 4.12, "deg": 150 }, "clouds": { "all": 0 }, "dt": 1702994482, "sys": { "type": 1, "id": 8841, "country": "AZ", "sunrise": 1702958314, "sunset": 1702991759 }, "timezone": 14400, "id": 587084, "name": "Bakı", "cod": 200 }
const weatherElement = document.querySelector(".hava");
if (weatherElement) {
    weatherElement.innerHTML = `${weatherData.name} ${Math.round(weatherData.main.temp)}°`
}