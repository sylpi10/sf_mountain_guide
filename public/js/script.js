window.onscroll = () => {
  backToTop();
  changeMenuOnscroll();
  // parrallaxOnTitle();
};

let backTop = document.querySelector(".back-top");

function backToTop() {
  if (
    document.body.scrollTop > 180 ||
    document.documentElement.scrollTop > 180
  ) {
    backTop.style.opacity = "1";
  } else {
    backTop.style.opacity = "0";
  }
}

function changeMenuOnscroll() {
  const menu = document.querySelector(".base-page nav");
  if (window.innerWidth > 991) {
    if (
      document.body.scrollTop > 360 ||
      document.documentElement.scrollTop > 360
    ) {
      menu.classList.add("scrolled-nav");
    } else {
      menu.classList.remove("scrolled-nav");
    }
  }
}

// mobile menu
const navToggler = document.querySelector(".navbar-toggler");
const navCollapse = document.querySelector(".navbar-collapse");

function setMenu(open) {
  navCollapse.classList.toggle("show", open);
  navToggler.setAttribute("aria-expanded", open);
}

navToggler.addEventListener("click", () => {
  setMenu(!navCollapse.classList.contains("show"));
});

// hide menu onclick
navCollapse.addEventListener("click", (e) => {
  if (e.target.closest("a")) {
    setMenu(false);
  }
});

// menu active link
document.querySelectorAll(`a[href$="${location.pathname}"]`).forEach((a) => {
  a.classList.add("active");
});

// alerts
document.querySelectorAll(".alert-success").forEach((alert) => {
  window.setTimeout(() => {
    alert.classList.add("fade-out");
    alert.addEventListener("transitionend", () => alert.remove());
  }, 2600);
});

// carousels (photos)
document.querySelectorAll(".carousel").forEach((carousel) => {
  const items = [...carousel.querySelectorAll(".carousel-item")];
  let current = items.findIndex((item) => item.classList.contains("active"));
  let timer;

  function show(index) {
    items[current].classList.remove("active");
    current = (index + items.length) % items.length;
    items[current].classList.add("active");
  }

  function autoplay() {
    clearInterval(timer);
    if (items.length > 1) {
      timer = setInterval(() => show(current + 1), 5000);
    }
  }

  carousel.querySelector(".carousel-control-prev")?.addEventListener("click", (e) => {
    e.preventDefault();
    show(current - 1);
    autoplay();
  });
  carousel.querySelector(".carousel-control-next")?.addEventListener("click", (e) => {
    e.preventDefault();
    show(current + 1);
    autoplay();
  });
  autoplay();
});


let jarallaxEnabled = false;

function updateJarallax() {
  if (typeof jarallax === "undefined") {
    return;
  }

  const elements = document.querySelectorAll("[data-jarallax]");
  if (!elements.length) {
    return;
  }

  const shouldEnable = window.innerWidth >= 992;

  if (shouldEnable && !jarallaxEnabled) {
    jarallax(elements);
    jarallaxEnabled = true;
  } else if (!shouldEnable && jarallaxEnabled) {
    jarallax(elements, "destroy");
    jarallaxEnabled = false;
  }
}

document.addEventListener("DOMContentLoaded", updateJarallax);
window.addEventListener("resize", updateJarallax);
