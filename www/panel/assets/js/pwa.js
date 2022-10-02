window.onload = () => {
	'use strict';
  
	if ('serviceWorker' in navigator) {
	  navigator.serviceWorker
			   .register('/server-worker.js');
	}
  }