## MELHORIAS
Neste projeto foram feitas um total de quatro melhorias, abaixo segue a descrição de cada uma delas:

-Filtro: Foi implementado um filtro para selecionar os ramais e os registros da fila por status e/ou nome ou agente;

-Filas: Foi implementado um card contendo as filas, nesse card é mostrado o nome do ramal que a fila está relacionada, o agente responsável por esta fila e há quanto tempo ela está com seu status. Além disso, foi criado a tabela de fila na base dados, onde foi setada uma relação entre ramais e filas, usando o nome do ramal como primary key, e o nome do ramal que consta na fila como foreign key. Além disso, a fila também é atualizada a cada 10 segundos na base de dados 

-Totalizador: Foi implementado um totalizador, permitindo a visualização do total de ramais que estão sendo listados, e o total de ramais em cada status disponível

-Autenticação: Foi implementado um sistema de login, para verificar a autenticação do usuário, onde é possível cadastrar um usuário, que é salvo no banco de dados e fazer o login com ele. Na tela do Painel de Monitoramento de Ramais, foi adicionado um botão no canto superior direito onde é possível efetuar o logout do sistema.