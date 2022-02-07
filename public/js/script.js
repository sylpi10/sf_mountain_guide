
window.onscroll = ()=> {
    backToTop();
      changeMenuOnscroll();
};

let backTop = document.querySelector('.back-top');

function backToTop() {
       if (document.body.scrollTop > 180 || document.documentElement.scrollTop > 180) {
         backTop.style.opacity = "1";
       } else {
        backTop.style.opacity = "0";
       }
 }

 function changeMenuOnscroll() {

  const menu = document.querySelector('nav');
  if(window.innerWidth > 800){
    if (document.body.scrollTop > 360 || document.documentElement.scrollTop > 360) {
      menu.classList.add('scrolled-nav');
    } else {
      menu.classList.remove('scrolled-nav');
    }
  }
 
}

// hide menu onclick
 $(function(){ 
  let navMain = $(".navbar-collapse"); // avoid dependency on #id
  // "a:not([data-toggle])" - to avoid issues caused
  // when you have dropdown inside navbar
  navMain.on("click", "a:not([data-toggle])", null, function () {
      navMain.collapse('hide');
  });
});

// menu active link
$(document).ready(function() {
  $('a[href$="' + location.pathname + '"]').addClass('active');
});


// alerts
$(".alert-success").alert();
window.setTimeout(function() { $(".alert-success").alert('close'); }, 2000);
