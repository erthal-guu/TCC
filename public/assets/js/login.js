
        function ValidarCampos() {
            var email = document.getElementById('email').value.trim();
            var senha = document.getElementById('senha').value.trim();

            if (email === "" || senha === "") {
                alert("Por favor, preencha todos os campos.");
                return false;
            }
            return true; 
        }