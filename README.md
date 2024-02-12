# EADPI
Uma simples plataforma EAD on-line desenvolvida para a Escola do Parlamento da Câmara Municipal de Itapevi (SP, Brasil)

A Câmara Municipal de Itapevi é a sede do poder legislativo municipal da cidade de Itapevi, localizada no estado de São Paulo, Brasil. A Escola do Parlamento de Itapevi é um setor da Câmara que oferece cursos, palestras e outras atividades aos servidores públicos e à população do município. O EADPI foi desenvolvido para substituir, mantendo os recursos básicos e necessários, a plataforma da Ensino Conectado antes utilizada para hospedar um dos seus cursos, mas que teve seu contrato com a Câmara de Itapevi finalizado. Houve necessidade de adaptar algumas das aulas do curso de Democracia e Cidadania para a nova plataforma.

A plataforma permite o cadastro de alunos mediante fornecimento de e-mail e senha. Após logar-se, o aluno pode se inscrever no curso disponível e acompanhá-lo por meio de vídeo-aulas. Cada curso é dividido em módulos, que são divididos em aulas, que são divididas em blocos. Cada aula pode ter um questionário de múltipla escolha vinculado. Por meio desses, a plataforma mede o desempenho do aluno e permite ao próprio gerar seu certificado ao terminar todos os questionários.

# Informações do EADPI
Tipo: Sistema web  
Versão atual: 1.0  
Linguagens de programação usadas: PHP e Javascript  
Bibliotecas PHP usadas: tFPDF (para geração de certificados em PDF), PHPMailer, Phinx  
Bibliotecas Javascript usadas: Lego.js (para gerar componentes client-side)  
Banco de dados usado: MySQL 8.0 / MariaDB 10.2  
Versão do PHP requerida: Mínimo 8.1  

# Instalação e funcionamento
Todos os arquivos e diretórios do branch "master" devem ser copiados para um diretório nomeado "eadpi" dentro da pasta acessível via localhost de seu servidor HTTP (neste caso, usou-se o IIS do Windows 11). O PHP deve estar instalado e configurado com as seguintes extensões habilitadas: mbstring, mysqli, openssl. O banco de dados MySQL/MariaDB deve ser criado de acordo com as migrações Phinx disponíveis na pasta 'db'.

No diretório base do seu servidor HTTP (fora do diretório eadpi), deve ser criado o arquivo de configuração com os dados para acesso ao banco de dados e a chave de criptografia (dados pessoais são criptografados ao serem salvos no banco). O arquivo deve se chamar "eadpi_config.ini", com o conteúdo abaixo, modificado as informações para seu caso:

<pre>
[database]  
servername="127.0.0.1"  
username="edpi_admin"       ;usuário do MySQL com permissões para SELECT, UPDATE, DELETE e INSERT  
password="Lt8Xn.NE05YyzAh("   ;senha do usuário do MySQL  
dbname="eadpi"               ;nome do banco de dados MySQL  
crypto="a1f15167b75cf1ae7e74a72bb454e5361ffa45"    ;chave de criptografia  
  
[regularmail]  
host="smtp.example.net"       ;Host SMTP para o envio de e-mail  
port=587                      ;Porta SMTP  
username="v-opus@example.net" ;Nome de usuário do servidor SMTP    
password="12345678"           ;Senha
sender="v-opus@example.net"
replyto="v-opus@example.net"  ;Endereço de resposta quando necessário
  
[urls]  
usefriendly=0                 ;Usar (1) ou não (0) URLs amigáveis. Esta configuração é lida pela classe de geração de URL
</pre>

O sistema em produção tem o acesso direto ao arquivo de configuração restringido. 

# Informações de contato
Desenvolvedor solo: Victor Opusculo  
E-mail: victor.ventura@uol.com.br