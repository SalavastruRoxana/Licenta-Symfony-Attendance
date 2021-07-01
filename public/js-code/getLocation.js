window.onload = function () {
    var classes = document.getElementById('prezenta');

    if (classes) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    document.getElementById("pozitia-lat").
                    setAttribute('value', position.coords.latitude)
                    document.getElementById("pozitia-lon").
                    setAttribute('value', position.coords.longitude)
                },
                () => {
                    alert("Permiteti detectarea locatiei pentru a realiza prezenta")
                },
                {
                    enableHighAccuracy: true,
                    timeout: 11000, //timpul maxim pe care il asteapta userul pana primeste coordonatele
                    maximumAge: 10
                }
            );
        } else {
            // Browser doesn't support Geolocation
            alert("Browser doesn't support Geolocation")
        }
    }
}