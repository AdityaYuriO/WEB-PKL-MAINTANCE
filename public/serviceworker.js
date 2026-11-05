const CACHE_NAME = "smkn1-v2";

const urlsToCache = [
  "/",
  "/images/logo.png"
];



// Install SW

self.addEventListener("install", event => {

  event.waitUntil(

    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))

  );

});



// Fetch

self.addEventListener("fetch", event => {
  // jangan cache manifest.json
  if (event.request.url.includes("manifest.json")) {
    return event.respondWith(fetch(event.request));
  }

  event.respondWith(
    fetch(event.request)
      .then(response => {
        const resClone = response.clone();
        caches.open(CACHE_NAME).then(cache => cache.put(event.request, resClone));
        return response;
      })
      .catch(() => caches.match(event.request))
  );
});

// Update SW

self.addEventListener("activate", event => {

  event.waitUntil(

    caches.keys().then(keys =>

      Promise.all(

        keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))

      )

    )

  );

});

