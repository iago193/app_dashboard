$(document).ready(() => {

    $('#documentacao').on('click', () => {
        $('#pagina').load('documentacao.html');
    })

        $('#suporte').on('click', () => {
        $('#pagina').load('suporte.html');
    })
	
})


$(document).ready(() => {
    $('#competencia').on('change', e => {

        let competencia = $(e.target).val();

        $.ajax({
            type: 'GET', 
            url: 'app.php', 
            data: `competencia=${competencia}`,  //x-www-form-urlencoded
            dataType: 'json',
            success: dados => { 
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
            },
            error: erro => { console.log(erro) }
        }); 

        //método, url, dados, sucesso, erro
    });
});
