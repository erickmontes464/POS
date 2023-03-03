window.addEventListener('load', function() {
    const miInput = document.getElementById('codigo');
    miInput.focus();
});
var numFilas = tabla.getElementsByTagName("tr").length;
document.getElementById("cont").innerHTML = numFilas-1;
