jQuery(document).ready(function ($) {
  // Function that starts the countdown timer for a given element.
  function startCountdown(timerElement, endTime) {
    if (endTime) {
      let countdownDate = new Date(endTime).getTime();

      function updateCountdown() {
        let now = new Date().getTime();
        let timeLeft = countdownDate - now;

        if (timeLeft <= 0) {
          timerElement
            .find(".countdown-display")
            .html('<span class="expired-message">Expired</span>');
          clearInterval(intervalId);
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

      updateCountdown(); // Run immediately.
      let intervalId = setInterval(updateCountdown, 1000);
    }
  }

  // Initialize countdown timers within a given context.
  function initializeCountdowns(context) {
    $(context)
      .find(".archive-sales-countdown-timer")
      .each(function () {
        let timerElement = $(this);
        // Skip if this element is already initialized.
        if (timerElement.data("initialized")) {
          return;
        }
        timerElement.data("initialized", true);
        let endTime = timerElement.data("end-date");
        startCountdown(timerElement, endTime);
      });
  }

  // Initialize countdowns on document ready.
  initializeCountdowns(document);

  // Set up a MutationObserver to watch for dynamically added nodes.
  var targetNode = document.body; // Or narrow this to a specific container.
  var config = { childList: true, subtree: true };

  var observer = new MutationObserver(function (mutationsList) {
    mutationsList.forEach(function (mutation) {
      mutation.addedNodes.forEach(function (addedNode) {
        if (addedNode.nodeType === 1) {
          // Only element nodes
          if (
            $(addedNode).is(".archive-sales-countdown-timer") ||
            $(addedNode).find(".archive-sales-countdown-timer").length > 0
          ) {
            initializeCountdowns(addedNode);
          }
        }
      });
    });
  });

  observer.observe(targetNode, config);
});
