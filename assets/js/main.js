/**
 * 住吉電機株式会社 サイト共通スクリプト
 * - モバイルナビ開閉
 * - ヘッダーのスクロール時シャドウ / トップへ戻るボタン
 * - ヒーロースライダー
 * - スクロール連動フェードイン
 * - 勤続年数の自動計算（求人ページ）
 */
(function () {
  "use strict";

  // モバイルナビ
  var toggle = document.querySelector(".nav-toggle");
  var nav = document.querySelector(".global-nav");
  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var expanded = toggle.getAttribute("aria-expanded") === "true";
      toggle.setAttribute("aria-expanded", String(!expanded));
      nav.classList.toggle("is-open", !expanded);
      document.body.style.overflow = expanded ? "" : "hidden";
    });
    nav.addEventListener("click", function (e) {
      if (e.target.closest("a")) {
        toggle.setAttribute("aria-expanded", "false");
        nav.classList.remove("is-open");
        document.body.style.overflow = "";
      }
    });
  }

  // ヘッダーのスクロール時シャドウ / トップへ戻る
  var header = document.querySelector(".site-header");
  var toTop = document.querySelector(".to-top");
  var onScroll = function () {
    if (header) header.classList.toggle("is-scrolled", window.scrollY > 8);
    if (toTop) toTop.classList.toggle("is-visible", window.scrollY > 600);
  };
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();
  if (toTop) {
    toTop.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // ヒーロースライダー
  var slides = document.querySelectorAll(".hero__slide");
  var dotsWrap = document.querySelector(".hero__dots");
  if (slides.length > 1) {
    var current = 0;
    var dots = [];
    slides.forEach(function (_, i) {
      var b = document.createElement("button");
      b.setAttribute("aria-label", "スライド" + (i + 1));
      b.addEventListener("click", function () { go(i); });
      dotsWrap.appendChild(b);
      dots.push(b);
    });
    var timer = null;
    var go = function (i) {
      slides[current].classList.remove("is-active");
      dots[current].classList.remove("is-active");
      current = (i + slides.length) % slides.length;
      slides[current].classList.add("is-active");
      dots[current].classList.add("is-active");
      clearInterval(timer);
      timer = setInterval(function () { go(current + 1); }, 6000);
    };
    slides[0].classList.add("is-active");
    dots[0].classList.add("is-active");
    timer = setInterval(function () { go(current + 1); }, 6000);
  } else if (slides.length === 1) {
    slides[0].classList.add("is-active");
  }

  // フェードイン
  if ("IntersectionObserver" in window) {
    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 }
    );
    document.querySelectorAll(".fade-in").forEach(function (el) {
      observer.observe(el);
    });
  } else {
    document.querySelectorAll(".fade-in").forEach(function (el) {
      el.classList.add("is-visible");
    });
  }

  // 勤続年数（WP版の [tenure] ショートコード相当）
  document.querySelectorAll("[data-hire-date]").forEach(function (el) {
    var hire = new Date(el.getAttribute("data-hire-date"));
    if (isNaN(hire)) return;
    var now = new Date();
    var years = now.getFullYear() - hire.getFullYear();
    var beforeAnniversary =
      now.getMonth() < hire.getMonth() ||
      (now.getMonth() === hire.getMonth() && now.getDate() < hire.getDate());
    if (beforeAnniversary) years -= 1;
    el.textContent = years + "年";
  });
})();
