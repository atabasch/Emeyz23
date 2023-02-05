function setupInstallPwa(){
  const installButton = document.getElementById('install-pwa-app');
  let beforeInstallPromptEvent;

  window.addEventListener("beforeinstallprompt", function(e) {
    e.preventDefault();
    beforeInstallPromptEvent = e
    installButton.style.display = 'block'
    installButton.addEventListener("click", function() {
      e.prompt();
    });
    installButton.hidden = false;
  });

  installButton.addEventListener("click", function() {
    beforeInstallPromptEvent.prompt();
  });


}


if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('/js/sw.js?v=4');
    setupInstallPwa();

  });
}

