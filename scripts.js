const buttons = document.querySelectorAll(".addtocart");

buttons.forEach((button) => {
    button.addEventListener('click', () => {
        const done = button.querySelector(".done"); // Wähle .done relativ zu diesem Button aus

        // Zeige sofort "Added"
        done.style.transform = "translate(0px)";

        // Warte z.B. 2 Sekunden, dann gehe zurück zum ursprünglichen Zustand
        setTimeout(() => {
            done.style.transform = "translate(-110%) skew(-40deg)";
        }, 1000); // 2000 Millisekunden = 2 Sekunden
    });
});
