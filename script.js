document.addEventListener('DOMContentLoaded', function () {
    // Observer les changements de visibilité pour différents éléments
    let observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Si l'élément est la machine à écrire
                if (entry.target.id === "machineAecrire") {
                    machineAEcrire(entry.target)
                }
                // Si l'élément est une barre de progression
                else if (entry.target.classList.contains("progress-bar")) {
                    animateBarresProgress(entry.target)
                }
                // Désinscrit l'élément après l'animation pour ne pas répéter
                observer.unobserve(entry.target)
            }
        })
    }, {
        root: null,
        rootMargin: '0px',
        threshold: 1
    })

    // Machine à écrire
    const texte = "Hello World!"
    let i = 0

    function machineAEcrire(elem) {
        function typeWriter() {
            if (i < texte.length) {
                elem.innerHTML += texte.charAt(i)
                i++
                setTimeout(typeWriter, getRandomInt(50, 300))
            }
        }
        typeWriter()
    }

    // Animer les barres de progression
    function animateBarresProgress(barre) {
        barre.style.width = "0"; 
    
        setTimeout(() => {
            const widthPercent = barre.dataset.target + "%";
            barre.style.width = widthPercent;
        }, 100);
    }
    
    // Générateur aléatoire pour l'effet machine à écrire
    function getRandomInt(min, max) {
        min = Math.ceil(min)
        max = Math.floor(max)
        return Math.floor(Math.random() * (max - min + 1)) + min
    }

    // Observer l'élément machine à écrire
    const elemMachineAecrire = document.getElementById("machineAecrire")
    if (elemMachineAecrire) {
        observer.observe(elemMachineAecrire)
    }

    // Observer chaque barre de progression
    document.querySelectorAll('.progress-bar').forEach(bar => {
        observer.observe(bar)
    })

    // Bouton de scroll UP
    const btnUp = document.getElementById("btnUP");
    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            btnUp.style.display = "block";
        } else {
            btnUp.style.display = "none";
        }
    });
});
