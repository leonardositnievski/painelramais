
$(document).ready(function () {
    $.ajax({
        url: 'http://localhost/painel-monitoramento-ramais/index.php',
        method: 'get',
        success: function (data) {
            if (data == 'Unauthenticated') {
                window.location.href = 'http://localhost/painel-monitoramento-ramais/login.html'
            }
            appendCards(data.ramais)
            appendQueue(data.fila)
            appendTotalizador(data.totalizador)
            setInterval(
                function () {
                    var status = $('#status').val()
                    var search = $('#search').val()
                    var url = 'http://localhost/painel-monitoramento-ramais/index.php?status=' + status + '&search=' + search
                    $.ajax({
                        url: url,
                        method: 'post',
                        data: {
                            'data': data.ramais,
                        },
                        success: function (data) {
                            appendCards(data[0])
                            appendQueue(data[1])
                            appendTotalizador(data[2])
                        },
                    })
                }, 10000)
        }
    })
})

function appendCards(data) {
    $('#cartoes').html('')
    for (let i in data) {
        if (!data[i].id) {
            data[i].id = ""
        }
        $('#cartoes').append(`<div id="${data[i].id}" class="cartao ${data[i].nome}">
                                <div>${data[i].nome}</div>
                                <span class="${data[i].status} icone-posicao"></span>
                                <span class="agente">${data[i].agente}</span>
                            </div>`)

        if (data[i].status == 'indisponivel') {
            $('.' + data[i].nome).css('background-color', '#A9A9A9')
        }
    }
}

function appendQueue(data) {
    $('.fila').html('')
    for (let i in data) {
        if (!data[i].id) {
            data[i].id = ""
        }
        if (data[i].status_updated_at == null) {
            var inicio = new Date(data[i].created_at)
            var fim = new Date()
            var diferenca = new Date(fim - inicio)
            var diffHrs = Math.floor((diferenca % 86400000) / 3600000)
            var diffMins = Math.round(((diferenca % 86400000) % 3600000) / 60000)
            var diff = diffHrs + 'h ' + diffMins + 'm'
        } else {
            var inicio = new Date(data[i].status_updated_at)
            var fim = new Date()
            console.log(diferenca)
            var diferenca = new Date(fim - inicio)
            var diffHrs = Math.floor((diferenca % 86400000) / 3600000)
            var diffMins = Math.round(((diferenca % 86400000) % 3600000) / 60000)
            var diff = diffHrs + 'h ' + diffMins + 'm'
        }
        $('.fila').attr('id', data[i].nome)
        $('.fila').append(`<div class='fila-reg'><em class="fila-${data[i].status}">${data[i].nomeRamal}</em><span>${data[i].agente}</span><span> - ${data[i].status} a: ${diff}</span></div>`)

        if (data[i].status == 'indisponivel') {
            $('.' + data[i].nome).css('background-color', '#A9A9A9')
        }
    }
}

function appendTotalizador(totalizador) {
    $('.totalizador').html('')
    $('.totalizador').append(`<div class='cardTotalizador'>Total de Ramais:<br> <span class='bolder'>${totalizador.totalRamais}</span></div><div class='borda'></div>
                            <div class='cardTotalizador'>Ramais Disponíveis:<br> <span class='bolder'>${totalizador.totalRamaisDisponiveis}</span></div><div class='borda'></div>
                            <div class='cardTotalizador'>Ramais Indisponíveis:<br> <span class='bolder'>${totalizador.totalRamaisIndisponiveis}</span></div><div class='borda'></div>
                            <div class='cardTotalizador'>Ramais Ocupados:<br> <span class='bolder'>${totalizador.totalRamaisOcupados}</span></div><div class='borda'></div>
                            <div class='cardTotalizador'>Ramais Pausados:<br> <span class='bolder'>${totalizador.totalRamaisPausados}</span></div><div class='borda'></div>
                            <div class='cardTotalizador'>Ramais em chamado:<br> <span class='bolder'>${totalizador.totalRamaisChamando}</span></div>`)
}

$('#formfiltro').submit(function (e) {
    e.preventDefault()

    var status = $('#status').val()
    var search = $('#search').val()
    var url = 'http://localhost/painel-monitoramento-ramais/index.php?status=' + status + '&search=' + search
    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {
            appendCards(data[0])
            appendQueue(data[1])
        },
        error: function (response) {
            $('#cartoes').html('')
            $('#cartoes').append('<div>Nenhum resultado foi encontrado para este filtro</div>')
            $('.fila').html('')
            $('.fila').append('<div>Nenhum resultado foi encontrado para este filtro</div>')
        }
    })

    return false
})

$('#logout').click(function () {
    var url = 'http://localhost/painel-monitoramento-ramais/index.php?logout=true'
    $.ajax({
        url: url,
        method: 'get',
        success: function (data) {
            if (data.sucesso) {
                window.location.href = 'http://localhost/painel-monitoramento-ramais/login.html'
            }
        },
    })
})