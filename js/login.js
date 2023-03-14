  $(document).ready(function () {
            $('#login').attr('class', 'active')
            $('#cadastrar').css('display', 'none')
        })

        $('.link').click(function () {
            if ($('#login').css('display') != 'none') {
                $('#login').css('display', 'none')
                $('#cadastrar').css('display', 'flex')
            } else {
                $('#login').css('display', 'flex')
                $('#cadastrar').css('display', 'none')
            }
        })

        $('#form-cad').submit(function (e) {
            e.preventDefault()
            var nome = $('#nome').val()
            var email = $('#email').val()
            var senha = $('#senha').val()
            var data = {
                "nome": nome,
                "email": email,
                "senha": senha
            }
            $.ajax({
                url: 'http://localhost/painel-monitoramento-ramais/indexApi.php',
                method: 'post',
                data: {
                    'cadastro': data
                },
                success: function (data) {
                    var data = JSON.parse(data)
                    $('.erro').attr('class', 'form-control')
                    if (data.erro) {
                        $('.msg-erro').remove()
                        $('#' + data.campo).attr('class', 'form-control erro')
                        $('.' + data.campo).append(`<span class='msg-erro'>${data.erro}</span>`)
                    }
                    if (data.sucesso) {
                        window.location.href = 'http://localhost/painel-monitoramento-ramais/index.html'
                    }
                }
            })
        })

        $('#form-log').submit(function (e) {
            e.preventDefault()
            var email = $('#emailLogin').val()
            var senha = $('#senhaLogin').val()
            var data = {
                "email": email,
                "senha": senha
            }
            $.ajax({
                url: 'http://localhost/painel-monitoramento-ramais/indexApi.php',
                method: 'post',
                data: {
                    'login': data
                },
                success: function (data) {
                    var data = JSON.parse(data)
                    $('.erro').attr('class', 'form-control')
                    if (data.erro) {
                        $('.msg-erro').remove()
                        $('#' + data.campo).attr('class', 'form-control erro')
                        $('.' + data.campo).append(`<span class='msg-erro'>${data.erro}</span>`)
                    }
                    if (data.invalido) {
                        $('.msg-erro').remove()
                        $('#login').append(`<span class='msg-erro'>${data.invalido}</span>`)
                    }
                    if (data.sucesso) {
                        window.location.href = 'http://localhost/painel-monitoramento-ramais/index.html'
                    }
                }
            })
        })
