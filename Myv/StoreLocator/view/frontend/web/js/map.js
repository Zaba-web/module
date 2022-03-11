function initStoreLocatorMap() {
    const TERNOPIL_CENTER = {lat: 49.5478, lng: 25.60296};
    const CURRENT_HOSTNAME = window.location.hostname;

    const addMarker = (coordinates, name, description, image, map) => {
        const marker = new google.maps.Marker({
            position: coordinates,
            map: map
        });

        const popup = new google.maps.InfoWindow({
            content: 
            `
                <b>${name}</b>
                <p>${description}</p>
                <img src='${getImage(image)}' alt='${name}' />
            `
        });

        marker.addListener('click', ()=>{
            popup.open(map, marker);
        });
    }
    
    const fetchStores = async () => {
        return fetch(`http://${CURRENT_HOSTNAME}/rest/default/V1/storelocator/store?searchCriteria`)
        .then((response) => {
            return response.json();
        });
    }

    const getCoordinates = store => {
        return {
            lat: parseFloat(store.latitude), 
            lng: parseFloat(store.longitude)
        }
    }

    const getImage = (image) => {
        const urlPattern = /^((http|https):\/\/)/;
        
        if(urlPattern.test(image)) {
            return image;
        }

        return `http://${CURRENT_HOSTNAME}/media/${image}`;
    }

    window.addEventListener("DOMContentLoaded", async ()=>{
        const element = document.getElementById("map");
        const options = {
            zoom: 13,
            center: TERNOPIL_CENTER
        }

        const map = new google.maps.Map(element, options);
    
        const stores = await fetchStores();
        stores.items.map(store => addMarker(getCoordinates(store), store.name, store.description, store.image, map));
    });
}