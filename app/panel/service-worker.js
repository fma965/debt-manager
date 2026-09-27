self.addEventListener('fetch', function(event) {
	// Let the browser handle page loads (e.g. the Discord OAuth return to /login.php) and non-GET requests
	if (event.request.mode === 'navigate' || event.request.method !== 'GET') return;

	event.respondWith(async function() {
	   try{
		 var res = await fetch(event.request);
		 var cache = await caches.open('cache');
		 cache.put(event.request.url, res.clone());
		 return res;
	   }
	   catch(error){
		 return caches.match(event.request);
		}
	  }());
  });
