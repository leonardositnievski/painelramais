## Painel de monitoramento de ramais
Este painel é um cenário fictício, onde há um painel de monitoramento de ramais. As informações do painel são atualizadas a cada 10 segundos utilizando ajax e estas informações são atualizadas na base de dados. Para verificar se está sendo atualizado na base de dados você poderá alterar as informações dos arquivos  lib\filas e lib\ramais. Este painel contém um filtro para selecionar os ramais e os registros da fila por status e/ou nome ou agente. Foi implementado um card contendo as filas, nesse card é mostrado o nome do ramal que a fila está relacionada, o agente responsável por esta fila e há quanto tempo ela está com seu status. Além disso, foi criado a tabela de fila na base dados, onde foi setada uma relação entre ramais e filas, usando o nome do ramal como primary key, e o nome do ramal que consta na fila como foreign key. Além disso, a fila também é atualizada a cada 10 segundos na base de dados. O painel contém um totalizador, permitindo a visualização do total de ramais que estão sendo listados, e o total de ramais em cada status disponível e um sistema de autenticação.

## Importante
1. O arquivo lib\filas simula as informações de um grupo de callcenter  
2. O arquivo lib\ramais simula as informações dos ramais  
3. Estes arquivos se completam  
4. Estes arquivos NÃO devem unidos em um só arquivo  
5. Estes arquivos poderão ser alterados apenas para teste do AJAX  
6. Para testar localmente o banco de dados deve ser obrigatoriamente o Mysql (devido as querys realizadas em mysqli)

## Testando Localmente
Para testar a atualização dos paineis e das filas é necessário alterar o arquivo ramais, ou o filas, porém, deve se ter cuidado, ao alterar valores. Abaixo segue quais colunas de lib\filas podem ser alterados e quais colunas de lib\ramais podem ser alterados.

Em ramais:
name/username pode ser alterado seguindo o mesmo formato de quatro digitos
Host pode ser alterado para '(unspecified)' 
Status para Unknown ou Ok

Em filas:
As filas não apresentam colunas, mas são separadas por frases.
veja um exemplo: SIP/7000 with penalty 1 (dynamic) (Not in use) has taken no calls yet Chaves

Cada frase pode ter o seu número SIP/ alterado, este número deve ser o nome de algum ramal;
O valor (Not in Use) entre parentêses representa o status da fila e pode ser alterado para: (Ring), (Unavailable), (paused), (paused) (Not in use), (In use);
O valor "no calls" representa o total de chamadas atendidas e pode ser alterado para um número inteiro;

O valor Chaves no final da frase representa o nome do atendente e pode ser alterado para qualquer outro nome próprio;




