const hamburger = document.querySelector(".hamburger");
const navmenu = document.querySelector(".nav-menu");
var messageArray = ["New projects coming soon..."];
var textPosition = 0;
var speed = 100;


hamburger.addEventListener("click", () =>{
    navmenu.classList.toggle("active");
})

document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () =>{
    hamburger.classList.remove("active");
    navmenu.classList.remove("active");
}))

typewriter = () =>{
    document.querySelector(".demo").innerHTML = messageArray[0].substring(0, textPosition) + "<span>\u25ae</span>";

    if(textPosition++ != messageArray[0].length)
        setTimeout(typewriter, speed);
}

window.addEventListener("load", typewriter);