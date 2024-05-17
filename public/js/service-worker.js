self.addEventListener('message', event => {
    if (event.data === 'playRingtone') {
        var audio = new Audio('ringtones/ring.mp3');
        audio.play();
    }
});
