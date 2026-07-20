const { post } = require("jquery");

$('#announcements-ticker').easyTicker({
    direction: 'up',
    easing: 'swing',
    speed: 'slow',
    interval: 2000,
    height: '400px',
    visible: 4,
    mousePause: 1,
    controls: {
        up: '',
        down: '',
        toggle: '',
        playText: 'Play',
        stopText: 'Stop'
    }
});
