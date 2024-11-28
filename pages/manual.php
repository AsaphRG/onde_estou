<?php require '../partials/init.php'; ?>

<section class="about-section">
    <h1 class="about-section">Manual de uso</h1>
    <p class="about-text">Este é o manual de uso do sistema com exemplos de funcionamento.</p>
    <p class="about-text">O desenvolvimento deste sistema se dá a fim de resolver um problema meu com o controle e acompanhamento dos livros e anotações que faço sobre cada um deles. A fim de evitar rascunhos perdidos e manter sempre o controle da página que estou resolvi consolidar todas essas informações em um sistema que me ajude a entender e gerir meu avanço literário.</p>
    <p class="about-text">Antes de registrar um livro temos que registrar um autor e uma editora.</p>
    <p class="about-text">Na tela inicial e no menu de navegação da página temos links que irão nos levar para as páginas de autor, editora, livro e manual (this). Cada uma destas telas tem um formato padrão com uma tabela com todos os registros do banco de dados e um botão para incluir um novo registro no canto superior esquerdo, conforme imagem:</p>
    <img src="/PIE3/public/assets/images/manual/include_new_register.png" alt="">
    <p class="about-text">Ao clicar em novo a seguinte tela irá aparecer:</p>
    <img src="/PIE3/public/assets/images/manual/new_author_page.png" alt="">
    <p class="about-text">Esta é uma página simples e com um recurso de autofocus. Assim que acessa o cursor é enviado diretamente para o campo de autor e então basta preencher o nome do autor e clicar em enviar, um novo registro será feito no banco de dados com o nome deste autor.</p>
    <p class="about-text">Algumas considerações que eu gostaria de aproveitar este momento para fazer:</p>
    <ol>
        <li>Eu nunca tive uma oportunidade real de usar uma lista ordenada enquanto desenvolvia um site, então resolvi aproveitar esse momento para usar este recurso, mas o primeiro item é um disclaimer sobre como nunca usei isso em um desenvolvimento real.</li>
        <li>Todos os formulários recebidos passam pela função formTreatment declarada em dbconnect/clean_data.php. Esta função é responsável por tratar todos os inputs a fim de evitar ataques XSS. Aqui está ela:</li>
        <pre>function formTreatment($form): array {
    $data = [];
    foreach ($form as $key => $value) {
        if (is_string($value)) {
            $data[$key] = htmlspecialchars($value);
        } else if (is_array($value)) {
            $data[$key] = formTreatment($value);
        } else {
            $data[$key] = $value;
        }
    }
    return $data;
}</pre>
        <li>Todos os inputs que serão passados para o banco de dados também recebem um segundo tratamento para evitar ataques de SQL Injection. Este eu não fiz uma fórmula para todos, mas ao invés disso optei por fazer uma estrutura para cada formulário a fim de garantir o máximo de flexibilidade para mim durante o desenvolvimento. Acredito que uma melhoria fosse possível, mas acho que vale deixar a refatoração para outro momento. Aqui está um exemplo de como foi feito usando a query do próprio autor:</li>
        <pre>$sql = $conn->prepare("INSERT INTO author (name) VALUES (?)");
$sql->bind_param('s', $data['name']);</pre>
        <li>Um outro sistema de segurança é o CSRF Token, que garante que todas as transações são feitas dentro da própria página. Para gerar este token estou usando duas funções do PHP para gerar valores aleatórios e depois criptografando-as com o algoritmo SHA-256 e salvando em Sessão, então ao enviar para a camada de modelo responsável pela manipulação dos dados é conferido se o token enviado no formulário é condizendo com o token salvo em sessão antes de executar a ação.</li>
        <p>Ao acessar cada página esse código é executado:</p>
        <pre>session_start();
$csrf_token = hash('sha256', uniqid(mt_rand(), true));
$_SESSION['csrf_token'] = $csrf_token;</pre>
        <p>E enviado o token para todos os formulários da página.</p>
        <pre>&lt;input type="hidden" name="csrf_token" value="<?= $csrf_token ?>"&gt;</pre>
        <p>E por fim é recebido e conferido ao chegar em algum model.</p>
        <pre>session_start();
[...]
if ($data['csrf_token'] == $_SESSION['csrf_token']) {
    [...]
} else {
    $message = "Token de segurança inválido.";
    header("Location: /PIE3/pages/author/authors.php?error=".urlencode($message));
    exit();
}</pre>
    </ol>
    <p class="about-text">Bem, parece que fiz bom proveito da lista ordenada, mas agora é hora de seguir em frente.</p>
    <p class="about-text">O funcionamento do campo editoras é idêntico ao campo de autores, são tabelas mais simples, mas que foram feitas dessa forma para evitar que a cada livro precisasse recadastrar esses campos, evitando inconsistências nos cadastros dos livros.</p>
    <p class="about-text">Vamos ver na prática como irá ficar então.</p>
    <p class="about-text">Vou cadastrar um livro que estou lendo atualmente que se mostrou um grande desafio para o sistema, mas vamos chegar lá. Primeiro aos autores.</p>
    <img src="/PIE3/public/assets/images/manual/new_author.png" alt="">
    <p class="about-text">Como pode ver ao fazer o registro de um autor aparece a mensagem dizendo que o cadastro foi feito com sucesso e o sistema automaticamente te redireciona para a página que lista o campo que você está incluindo objetos. O livro em questão tem dois autores, então vou cadastrar o segundo.</p>
    <img src="/PIE3/public/assets/images/manual/new_author_miswrite.png" alt="">
    <p class="about-text">Droga, errei o nome do autor. Então o que faço é entrar novamente no registro desse mesmo autor e atualizá-lo. Veja:</p>
    <img src="/PIE3/public/assets/images/manual/new_author_correcting.png" alt="">
    <p class="about-text">Ao clicar no registro do autor sou levado a um novo formulário onde posso simplesmente selecionaro que quero alterar e clicar em salvar em seguida.</p>
    <img src="/PIE3/public/assets/images/manual/new_author_corrected.png" alt="">
    <p class="about-text">Pronto, nome corrigido, felizmente eu criei um campo pra Update, se ele entendesse português acho que não ia gostar de como errei.</p>
    <p class="about-text">Um detalhe importante é que poderíamos tentar acessar um autor pela url através do seu ID do banco de dados. Como é o caso a seguir:</p>
    <img src="/PIE3/public/assets/images/manual/accessing_via_id.png" alt="">
    <p class="about-text">O primeiro que cadastrei foi o Foster Provost, então ao inserir o ID 1 na url ele me retorna este objeto do banco de dados, mas se eu inserir o ID 3, por exemplo?</p>
    <img src="/PIE3/public/assets/images/manual/error_accessing_via_id.png" alt="">
    <p class="about-text">Eu recebo um erro dizendo que esse ID não foi encontrado, encerra a execução do código e volta para a página de listagem de objetos.</p>
    <p class="about-text">A dinâmica do campo de editora é muito semelhante, então vou apenas cadastrar e pular os detalhes. Ficou assim:</p>
    <img src="/PIE3/public/assets/images/manual/new_publisher.png" alt="">
    <p class="about-text">Vamos então para a estrela do sistema, o cadastro do livro.</p>
    <img src="/PIE3/public/assets/images/manual/books.png" alt="">
    <p class="about-text">É possível ver que a estrutura desta tela é um pouco diferente das outras, tem mais algumas informações, mas cadê o autor e a editora? Esses campos vamos poder ver ao criar um novo livro ou atualizar um livro existente.</p>
    <img src="/PIE3/public/assets/images/manual/new_book.png" alt="">
    <p class="about-text">Ele tem mais alguns campos, como é possível de se ver. Dois campos de texto, dois de número, dois selects e um checkbox. Vamos preenche-los e vai ficar mais fácil de visualizar.</p>
    <img src="/PIE3/public/assets/images/manual/new_book_filled.png" alt="">
    <p class="about-text">Assim fica o formulário preenchido. E como pode ver tem dois autores selecionados já que o livro possui dois autores. Visualmente é uma atividade fácil de se fazer, mas este se mostrou um campo difícil de se gerenciar no banco de dados.</p>
    <img src="/PIE3/public/assets/images/manual/new_book_registered.png" alt="">
    <center><h1>E finalmente livro cadastrado!</h1></center>
    <p>Agora para acessar o livro basta clicar nele e ao acessá-lo podemos criar notas sobre o livro.</p>
    <img src="/PIE3/public/assets/images/manual/new_book_notes.png" alt="">
    <p>O processo é tão simples quanto deveria ser. O campo de nota é obrigatório, as páginas são opcionais. Uma vez preenchido o formulário basta clicar no botão com um símbolo de V que o mesmo será submetido para cadastro. A borracha apaga todo o formulário de uma vez.</p>
    <img src="/PIE3/public/assets/images/manual/new_book_new_note.png" alt="">
    <p>Uma vez registrada a nota o sistema faz a contagem das notas cadastradas e as já cadastradas ganham um botão vermelho para deleção da nota.</p>
    <center><h1>Fim!</h1></center>
</section>

<?php require ABSOLUTE_PATH.'/partials/end.php'; ?>