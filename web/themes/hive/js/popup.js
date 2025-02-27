(function (Drupal, once) {
  Drupal.behaviors.hivePopup = {
    attach(context) {

      once('hive-popup', document.body, context).forEach(() => {
        const popup = document.createElement('div');
        popup.id = 'hive-popup';
        popup.className = 'hive-popup-overlay';
        popup.innerHTML = `
          <div class="hive-popup-content">
            <p>Welcome to Hive! Enjoy your stay.</p>
            <button id="close-popup">Close</button>
          </div>
        `;

        document.body.appendChild(popup);

        document.getElementById('close-popup').addEventListener('click', () => {
          popup.style.display = 'none';
        });

        setTimeout(() => {
          popup.style.display = 'block';
        }, 1000);
      });
    }
  };
})(Drupal, once);
