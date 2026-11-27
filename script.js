// ===== NAVBAR SCROLL + ACTIVE LINK =====
const navbar = document.getElementById("navbar");
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll(".nav-link");

const updateActiveLink = () => {
    let current = "";
    sections.forEach(section => {
        if (window.scrollY >= section.offsetTop - 100) {
            current = section.id;
        }
    });
    navLinks.forEach(link => {
        link.classList.toggle("active", link.getAttribute("href") === `#${current}`);
    });
};

window.addEventListener("scroll", () => {
    navbar.classList.toggle("scrolled", window.scrollY > 50);
    updateActiveLink();
});

// ===== HAMBURGER MENU =====
const hamburger = document.getElementById("hamburger");
const navMenu = document.getElementById("navMenu");

hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");
});

navLinks.forEach(link => {
    link.addEventListener("click", () => {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
    });
});

// ===== ANIMASI FADE-UP (Intersection Observer) =====
const fadeElements = document.querySelectorAll('.fade-up');

const fadeObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            fadeObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

fadeElements.forEach(el => fadeObserver.observe(el));

// ===== NOTIFIKASI =====
const notification = document.getElementById("notification");
const notifText = document.getElementById("notifText");

const showNotification = (text) => {
    notifText.textContent = text;
    notification.classList.add("show");
    setTimeout(() => notification.classList.remove("show"), 2500);
};

// ===== FORM KONTAK -> KIRIM KE ADMIN =====
document.getElementById("kontakForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector("button[type=submit]");
    const fd = new FormData();
    fd.append("csrf", document.getElementById("kCsrf").value);
    fd.append("name", document.getElementById("nama").value.trim());
    fd.append("contact", document.getElementById("kontakInfo").value.trim());
    fd.append("message", document.getElementById("pesan").value.trim());
    btn.disabled = true;
    try {
        const r = await fetch("proses/kirim_pesan.php", { method: "POST", body: fd });
        const d = await r.json();
        showNotification(d.msg);
        if (d.ok) document.getElementById("pesan").value = "";
    } catch (err) {
        showNotification("Gagal mengirim pesan. Coba lagi.");
    }
    btn.disabled = false;
});

// ===== PAKSA TOMBOL BELANJA KE KONTAK =====
const btnBelanja = document.getElementById("btnBelanja");
if (btnBelanja) {
    btnBelanja.addEventListener("click", function (e) {
        e.preventDefault();
        const targetKontak = document.getElementById("etalase");
        if (targetKontak) {
            window.scrollTo({
                top: targetKontak.offsetTop, // Langsung ke atas section kontak
                behavior: "smooth"
            });
        }
    });
}

// ===== SMOOTH SCROLL UNTUK NAVBAR =====
document.querySelectorAll('a[href^="#"]:not(#btnBelanja)').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            e.preventDefault();
            window.scrollTo({
                top: target.offsetTop - 70,
                behavior: "smooth"
            });
        }
    });
});