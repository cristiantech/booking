// JavaScript Document
function validate() {
  var docId = $("input#documentoIdentificacion").val();
  document.getElementById('recurrencia').innerHTML = '';

  if (docId.length == 10) {
    var a1, a2, a3, a4, a5, a6, a7, a8, a9, a10, at, decenasup, validacion, validador;

    a1 = parseInt(docId.substr(0, 1));
    a2 = parseInt(docId.substr(1, 1));
    a3 = parseInt(docId.substr(2, 1));
    a4 = parseInt(docId.substr(3, 1));
    a5 = parseInt(docId.substr(4, 1));
    a6 = parseInt(docId.substr(5, 1));
    a7 = parseInt(docId.substr(6, 1));
    a8 = parseInt(docId.substr(7, 1));
    a9 = parseInt(docId.substr(8, 1));

    a10 = parseInt(docId.substr(9, 1));

    /* Cálculo de posiciones impares */
    a1 = a1 * 2;
    if (a1 >= 10) {
      a1 = a1 - 9;
    }

    a3 = a3 * 2;
    if (a3 >= 10) {
      a3 = a3 - 9;
    }

    a5 = a5 * 2;
    if (a5 >= 10) {
      a5 = a5 - 9;
    }

    a7 = a7 * 2;
    if (a7 >= 10) {
      a7 = a7 - 9;
    }

    a9 = a9 * 2;
    if (a9 >= 10) {
      a9 = a9 - 9;
    }

    at = a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9;

    decenasup = Math.ceil(at / 10) * 10;

    validacion = decenasup - at;

    if (validacion == a10) {
      validador = 1;
    } else {
      validador = 0;
    }
  }
}
