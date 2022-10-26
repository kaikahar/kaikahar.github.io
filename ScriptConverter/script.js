var words;

function ueyTouly(){
    words = document.getElementById("UEYscript").value;
    words = words.replace(/ئا/g, "a");
    words = words.replace(/ا/g, "a");
    words = words.replace(/ە/g, "e");
    words = words.replace(/ئە/g, "e");
    words = words.replace(/ب/g, "b");
    words = words.replace(/پ/g, "p");
    words = words.replace(/ت/g, "t");
    words = words.replace(/ج/g, "j");
    words = words.replace(/چ/g, "ch");
    words = words.replace(/خ/g, "x");
    words = words.replace(/د/g, "d");
    words = words.replace(/ر/g, "r");
    words = words.replace(/ز/g, "z");
    words = words.replace(/ژ/g, "zh");
    words = words.replace(/س/g, "s");
    words = words.replace(/ش/g, "sh");
    words = words.replace(/غ/g, "gh");
    words = words.replace(/ف/g, "f");
    words = words.replace(/ق/g, "q");
    words = words.replace(/ك/g, "k");
    words = words.replace(/گ/g, "g");
    words = words.replace(/ڭ/g, "ng");
    words = words.replace(/ل/g, "l");
    words = words.replace(/م/g, "m");
    words = words.replace(/ن/g, "n");
    words = words.replace(/ھ/g, "h");
    words = words.replace(/ئو/g, "o");
    words = words.replace(/و/g, "o");
    words = words.replace(/ئۇ/g, "u");
    words = words.replace(/ۇ/g, "u");
    words = words.replace(/ئۆ/g, "ö");
    words = words.replace(/ۆ/g, "ö");
    words = words.replace(/ئۈ/g, "ü");
    words = words.replace(/ۈ/g, "ü");
    words = words.replace(/ۋ/g, "w");
    words = words.replace(/ئې/g, "ë");
    words = words.replace(/ې/g, "ë");
    words = words.replace(/ئى/g, "i");
    words = words.replace(/ى/g, "i");
    words = words.replace(/ي/g, "y");
    words = words.replace(/ئ/g, "");
    words = words.replace(/،/g, ",");
    
    document.getElementById('ULYscript').value = words;
}


function eraseText() {
    document.getElementById("UEYscript").value = "";
}