// Initialize Pusher with your app key and cluster
var pusher = new Pusher('993fac7a22d18eba2fa6', {
  cluster: 'ap2'
});

// Subscribe to the 'eatinsta' channel
var channel = pusher.subscribe('eatinsta');

function play() {
  var audio = document.getElementById('ringtone');
  setTimeout(function () {
    audio.pause();
    audio.currentTime = 0; // Reset audio playback position
  }, 10000);
  audio.play();
}




function pause() {
  var audio = document.getElementById('ringtone');
  
  audio.pause();
}
function showToast(title, message) {
  var toastLiveExample = document.getElementById("liveToast");
  var timeElement = toastLiveExample.querySelector("#time");
  var messageElement = toastLiveExample.querySelector("#message");
  var close = toastLiveExample.querySelector("#close");
  timeElement.innerText = title;
  messageElement.innerText = message;

  // Assigning the onclick event handler to the close button
  close.onclick = function() {
      pause();  // Call the pause function when the close button is clicked
  };

  const toastBootstrap = new bootstrap.Toast(toastLiveExample);
  toastBootstrap.show();
}


// Bind to the 'orders' event on the channel
channel.bind('orders', function (data) {
  
  console.log(data);
  

  showToast(data.title,data.message);

  if(data.order_status=='a_sent'){
    play();
  }
  
  const targetRoute = '/admin/pusher/events?message='+data.message+'&title='+data.title+'&order_status=' + data.order_status + '&delivery_status=' + data.delivery_status;
  setTimeout(function () {
  window.location.href = targetRoute;

  }, 2000);

});




