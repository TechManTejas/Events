(function (Drupal, drupalSettings) {
  Drupal.behaviors.eventsCountdown = {
    attach: function (context, settings) {
      console.log("Drupal behavior attached");

      if (!settings.events_timer || !settings.events_timer.eventStartTime) {
        console.error("Event start time is missing.");
        return;
      }

      let eventStartTime = settings.events_timer.eventStartTime * 1000;
      let countdownElements = document.querySelectorAll(".countdown-timer");

      console.log(countdownElements)

      countdownElements.forEach((element) => {
        function updateCountdown() {
          let now = new Date().getTime();
          let timeLeft = eventStartTime - now;

          if (timeLeft <= 0) {
            element.innerHTML = "Event Started!";
          } else {
            let days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            let hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            element.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
          }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
      });
    }
  };
})(Drupal, drupalSettings);
