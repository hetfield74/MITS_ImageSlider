document.addEventListener( 'DOMContentLoaded', function() {
  var elms = document.getElementsByClassName('mits_imageslider');
  for (var i = 0; i < elms.length; i++) {
    new Splide(elms[i], {
      type: 'fade',
      role: 'group',
      autoplay: true,
      interval: 5000,
      rewind: true,
      pagination: true,
      speed: 1000,
    }).mount();
  }
});