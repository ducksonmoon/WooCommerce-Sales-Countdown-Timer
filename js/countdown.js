jQuery(document).ready(function ($) {
  function startCountdown(timerElement, endTime) {
    if (endTime) {
      let countdownDate = new Date(endTime).getTime();

      function updateCountdown() {
        let now = new Date().getTime();
        let timeLeft = countdownDate - now;

        if (timeLeft <= 0) {
          // Handle expiration
          timerElement.html('<span class="expired-message">Sale Ended</span>');
          return;
        }

        let days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        let hours = Math.floor(
          (timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
        );
        let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        timerElement.find(".days").text(days < 10 ? "0" + days : days);
        timerElement.find(".hours").text(hours < 10 ? "0" + hours : hours);
        timerElement
          .find(".minutes")
          .text(minutes < 10 ? "0" + minutes : minutes);
        timerElement
          .find(".seconds")
          .text(seconds < 10 ? "0" + seconds : seconds);
      }

      updateCountdown(); // Initial call
      setInterval(updateCountdown, 1000); // Update every second
    } else {
      timerElement.html(
        '<span class="no-end-date-message">Limited time offer!</span>'
      );
    }
  }

  // Initialize timers on product single page
  let singleTimer = $("#sales-countdown-timer");
  if (singleTimer.length) {
    startCountdown(singleTimer, singleTimer.data("end-date"));
  }

  // Initialize timers on product archive pages
  $(".archive-sales-countdown-timer").each(function () {
    let archiveTimer = $(this);
    startCountdown(archiveTimer, archiveTimer.data("end-date"));
  });
});
